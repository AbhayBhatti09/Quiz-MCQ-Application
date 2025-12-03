<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Quizzes as Quiz;
use Illuminate\Support\Facades\Hash;
use App\Models\QuizSchedule;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;

class UserWiseQuizController extends Controller
{
    public function index()
    {
        $users = User::where('role_id', 2)->latest()->get();
        return view('quiz.users.index', compact('users'));
    }

    public function create()
    {
        return view('quiz.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:6|confirmed",
        ]);

        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "role_id" => 2,
            "password" => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('quiz.users.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|unique:users,email," . $user->id,
            "password" => "nullable|min:6|confirmed",
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(string $id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully!');
    }


    public function userwiseQuizlist(){

        $schedules = QuizSchedule::with(['user', 'quiz'])
                    ->orderBy('id', 'DESC')
                    ->get();

    return view('quiz.schedule.index', compact('schedules'));
    }

    public function createSchedule(){
        $quizs=Quiz::where('is_active',true)->latest()->get();
         $users = User::where('role_id', 2)->latest()->get();
        return view('quiz.schedule.create',compact('quizs','users'));
    }

    public function scheduleStore(Request $request)
    {
        $request->validate([
            'quiz_id' => 'required',
            'user_id' => 'required',
            'schedule_at' => 'required|date',
        ]);

        QuizSchedule::create([
            'quiz_id' => $request->quiz_id,
            'user_id' => $request->user_id,
            'schedule_at' => $request->schedule_at,
            'is_processed' => $request->is_processed ? 1 : 0,
        ]);

        return redirect()->route('schedule.index')
            ->with('success', 'Quiz scheduled successfully!');
    }

       public function destroySchedule($id)

    {
    
        $schedule = QuizSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('schedule.index')->with('success', 'Schedule deleted successfully.');
    }

    public function toggleProcess(Request $request)
        {
            $schedule = QuizSchedule::findOrFail($request->id);

            $schedule->is_processed = $request->status;
            $schedule->save();

            return response()->json(['success' => true]);
        }
    public function userSchedule()
{
    $userId = auth()->id();
     $now = now();
       $TenMinutesAgo = now()->subMinutes(10);
       
    $attemptedQuizIds = QuizAttempt::where('user_id', $userId)
                        ->pluck('quiz_id')
                        ->toArray();
    $schedules = QuizSchedule::with('quiz')
                    ->where('user_id', $userId)
                    //->whereNotIn('quiz_id', $attemptedQuizIds)
                    ->where('schedule_at','>',$TenMinutesAgo)
                     //->where('schedule_at','>',$now)
                    ->orderBy('schedule_at', 'ASC')
                    ->get();

    return view('quiz.users.schedule', compact('schedules'));
}


}
