<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = 'http://host.docker.internal:8000/api/categories';
    }

    public function index()
    {
        $response = $this->makeRequest('GET', '');
        $categories = json_decode($response, true);
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'parent_id' => 'nullable|exists:categories,id']);

        $payload = [
            'name' => $request->input('name'),
            'parent_id' => $request->input('parent_id'),
        ];

        $response = $this->makeRequest('POST', '', $payload);
        return redirect()->back()->with('message', 'Category created!');
    }

    public function update(Request $request, $id)
    {
        $payload = ['name' => $request->input('name')];

        $response = $this->makeRequest('PUT', "/$id", $payload);
        return redirect()->back()->with('message', 'Category updated!');
    }

    public function destroy($id)
    {
        $user_id = auth()->id();
        $response = $this->makeRequest('DELETE', "/$id?uid=$user_id");
        return redirect()->back()->with('message', 'Category deleted!');
    }

    protected function makeRequest($method, $endpoint, $data = [])
    {
        $ch = curl_init();

        $url = $this->apiBaseUrl . $endpoint;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = ['Accept: application/json'];

        switch (strtoupper($method)) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                $headers[] = 'Content-Type: application/x-www-form-urlencoded';
                break;
            case 'PUT':
            case 'PATCH':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                $headers[] = 'Content-Type: application/x-www-form-urlencoded';
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch));
        }

        curl_close($ch);

        if ($httpCode >= 400) {
            throw new \Exception("API Error: $result");
        }

        return $result;
    }

    public function showImportForm()
    {
        return view('categories.import');
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('file')->getRealPath();
        $fileObj = new \SplFileObject($file);
        $fileObj->setFlags(\SplFileObject::READ_CSV);
        $fileObj->setCsvControl(',');

        $fileObj->rewind(); // Move to start
        $fileObj->next();   // Skip header

        $rawRows = [];
        while (!$fileObj->eof()) {
            $row = $fileObj->fgetcsv();

            if (count($row) < 2 || is_null($row[0])) {
                continue;
            }

            [$name, $parentName] = $row;
            $rawRows[] = [
                'name' => trim($name),
                'parent' => trim($parentName) ?: null,
            ];
        }

        // Load existing categories from DB
        $existing = Category::pluck('path', 'name')->toArray(); // name => path

        // Resolve paths using memory
        $resolved = $existing; // will be appended with new entries
        $batch = [];
        $maxTries = count($rawRows) * 2;

        while (!empty($rawRows) && $maxTries-- > 0) {
            
            foreach ($rawRows as $key => $item) {
                $name = $item['name'];
                $parentName = $item['parent'];

                if (empty($name)) {
                    continue;
                }

                if ($parentName === null || isset($resolved[$parentName])) {
                    $path = $parentName ? $resolved[$parentName] . '/' . $name : $name;
                    $resolved[$name] = $path;

                    $batch[] = [
                        'name' => $name,
                        'path' => $path,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    unset($rawRows[$key]); // remove from pending
                }
            }
        }

        if (!empty($rawRows)) {
            foreach ($rawRows as $missing) {
                Log::warning("Unable to resolve parent for: {$missing['name']} (parent: {$missing['parent']})");
            }
        }

        // Insert into DB in chunks
        $chunks = array_chunk($batch, 2);
        foreach ($chunks as $chunk) {
            Category::insert($chunk);
        }
        Cache::forget('categories_index');

        return redirect()->route('categories.index')->with('message', 'CSV import completed');
    }



}
