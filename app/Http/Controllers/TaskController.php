<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class TaskController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->only(['title', 'description', 'status']);

        if (is_array($data['status'])) {
            $data['status'] = $data['status']['name'];
        }

        $data['id'] = uniqid();

        $data['created_at'] = now();

        $filename = 'tasks.json';
        $path = storage_path('app/' . $filename);

        if (File::exists($path)) {
            $jsonContent = Storage::get($filename);
            $tasks = json_decode($jsonContent, true);

            if ($tasks === null && json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Erro ao decodificar o JSON existente'], 500);
            }
        } else {
            $tasks = [];
        }

        $tasks[] = $data;

        $jsonData = json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($jsonData === false) {
            return response()->json(['error' => 'Erro ao codificar os dados para JSON'], 500);
        }

        Storage::put($filename, $jsonData);

        return response()->json(['message' => 'Tarefa criada com sucesso'], 201);
    }

    public function findAll()
    {
        $filename = 'tasks.json';
        $path = storage_path('app/' . $filename);

        if (File::exists($path)) {
            $jsonContent = Storage::get($filename);
            $data = json_decode($jsonContent, true);
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }

    public function findById($id)
    {
        $filename = 'tasks.json';
        $path = storage_path('app/' . $filename);

        if (File::exists($path)) {
            $jsonContent = Storage::get($filename);
            $tasks = json_decode($jsonContent, true);

            $task = array_filter($tasks, function($task) use ($id) {
                return $task['id'] === $id;
            });

            if (!empty($task)) {
                return response()->json(array_shift($task));
            } else {
                return response()->json(['error' => 'Tarefa não encontrada'], 404);
            }
        } else {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['title', 'description', 'status']);

        if (isset($data['status']) && is_array($data['status'])) {
            $data['status'] = $data['status']['name'];
        }

        $filename = 'tasks.json';
        $path = storage_path('app/' . $filename);

        if (!File::exists($path)) {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }

        $jsonContent = Storage::get($filename);
        $tasks = json_decode($jsonContent, true);

        if ($tasks === null && json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Erro ao decodificar o JSON existente'], 500);
        }

        $taskIndex = null;
        foreach ($tasks as $index => $task) {
            if ($task['id'] === $id) {
                $taskIndex = $index;
                break;
            }
        }

        if ($taskIndex !== null) {
            $tasks[$taskIndex] = array_merge($tasks[$taskIndex], $data);

            $tasks[$taskIndex]['updated_at'] = now();

            $jsonData = json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            if ($jsonData === false) {
                return response()->json(['error' => 'Erro ao codificar os dados para JSON'], 500);
            }

            Storage::put($filename, $jsonData);

            return response()->json(['message' => 'Tarefa atualizada com sucesso']);
        } else {
            return response()->json(['error' => 'Tarefa não encontrada'], 404);
        }
    }

    public function deleteById($id)
    {
        $filename = 'tasks.json';
        $path = storage_path('app/' . $filename);

        if (File::exists($path)) {
            $jsonContent = Storage::get($filename);
            $tasks = json_decode($jsonContent, true);

            // Filtra as tarefas para remover a com o ID especificado
            $updatedTasks = array_filter($tasks, function($task) use ($id) {
                return $task['id'] !== $id;
            });

            // Se o número de tarefas for menor do que o original, significa que foi removido
            if (count($updatedTasks) < count($tasks)) {
                $jsonData = json_encode($updatedTasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                // Salva o JSON atualizado no arquivo
                Storage::put($filename, $jsonData);

                return response()->json(['message' => 'Tarefa deletada com sucesso']);
            } else {
                return response()->json(['error' => 'Tarefa não encontrada'], 404);
            }
        } else {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }

}
