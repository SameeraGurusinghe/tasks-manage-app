<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendEmailJob;
use App\Jobs\SendCompletedEmailJob;

class TaskController extends Controller
{
    // public function __construct()
    // {
    //     // Verify that the user is authenticated before allowing access to Application
    //     $this->middleware('auth');

    //     // Prevent the page from being cached to protect sensitive information
    //     $this->middleware(function ($request, $next) {
    //         $response = $next($request);
    //         $response->headers->set('Cache-Control','nocache, no-store, max-age=0, must-revalidate');
    //         return $response;
    //     });
    // }

    //Display all tasks or filtered by request parameters.
    public function index(Request $request)
    {
        $tasks = Task::query();
    
        // Filter by completed or incomplete status
        if (request('filter') == 'completed') {
            $tasks->completed();
            $completed = true;
        } elseif (request('filter') == 'incomplete') {
            $tasks->incomplete();
            $completed = false;
        } else {
            $completed = null;
        }
    
        // Filter by due date
        //$due_date = request('due_date'); // Define and initialize $due_date variable
        if ($due_date = request('due_date')) {
            $tasks->byDueDate($due_date);
        }
    
        // Filter by overdue status
        if (request('filter') == 'overdue') {
            $tasks->overdue();
            $overdue = true; // Define and initialize $overdue variable
        } else {
            $overdue = false; // Define and initialize $overdue variable
        }
    
        $tasks = $tasks->orderBy('due_date')->get();
    
        //return view('tasks.index', compact('tasks', 'completed', 'due_date', 'overdue'));
        if ($request->wantsJson()) {
            // Handle API request
            return response()->json(['data' => $tasks]);
        } else {
            // Handle web request
            return view('tasks.index', compact('tasks', 'completed', 'due_date', 'overdue'));
        }
    }

    public function show($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'error' => 'Task not found'
            ], 404);
        }

        return response()->json([
            'data' => $task
        ]);
    }
     
    // Create a new task
    public function create()
    {
        return view('tasks.create');
    }
    
    // Store user inputed data into the database
    public function store(Request $request)
    {
        // Input fields validation
        $validatedData = $this->validateTask($request);
    
        $validatedData['completed'] = false;
        $validatedData['user_id'] = Auth::id();
    
        $task = Task::create($validatedData);
    
        $user = $request->user();

        SendEmailJob::dispatch($task, $user);
    
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }
    
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }
    
    // Update selected task
    public function update(Request $request, Task $task)
    {
        // Input fields validation
        $validatedData = $this->validateTask($request);
    
        $original_completed = $task->completed;
        $validatedData['completed'] = $request->has('completed');
    
        $task->update($validatedData);
    
        // Send an email to user when user has update task.
        if (!$original_completed && $request->completed) {
            $user = $request->user();

            SendCompletedEmailJob::dispatch($task, $user);
        }
    
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }
    
    // Delete a task
    public function destroy(Task $task)
    {
        $task->delete();
    
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    private function validateTask(Request $request)
    {
        return $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'due_date' => 'required|date',
        ]);
    }

}