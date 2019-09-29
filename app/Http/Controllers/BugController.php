<?php

namespace App\Http\Controllers;

use App\Bug;
use Auth;
use App\Http\Requests\BugCreateRequest;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;

class BugController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($filter = null)
    {
        if($filter == 'all'){
            $data['bugs'] = Bug::get();
        }else{
            $data['bugs'] = Bug::notFinished()->get();
        }
        return view('bugs.all', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bugs.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BugCreateRequest $request)
    {
        $user = Auth::user();

        $bug = new Bug;
        $bug->title = $request->title;
        $bug->description = $request->description;
        $bug->type = $request->type;
        $bug->user_id = $user->id;
        $saved = $bug->save();
        if(!$saved){
            return redirect()->back()->withInput()->with('insert_error', 'Something went wrong during saving this Ticket in the database, please try again later.');
        }
        $body = $user->name . ' has assigned to you a new Ticket #'.$bug->id.' "'.$bug->title.'".';
        $this->mail('New Ticket!', $body, User::find(5));
        return redirect()->route('showbug',['bug' => $bug])->with('success','Ticket is saved successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function start(Bug $bug)
    {
        $user = Auth::user();
        if ($user->developer == 1) {
            $bug->state = 1;
            $saved = $bug->save();
            if (!$saved) {
                return redirect()->back()->withInput()->with('insert_error', 'Something went wrong during starting this Ticket, please try again later.');
            }
            $body = $user->name . ' has started development of the Ticket #' . $bug->id . ' "' . $bug->title . '". please have some patience! We will let you know as soon as this ticket is on "Test" state.';
            $this->mail('Ticket development is started!', $body, $bug->user);
            return redirect()->route('showbug', ['bug' => $bug])->with('success', 'Ticket has been started!');
        } else {
            abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function test(Bug $bug)
    {
        $user = Auth::user();
        if($user->developer == 1){
            $bug->state = 2;
            $saved = $bug->save();
            if(!$saved){
                return redirect()->back()->withInput()->with('insert_error', 'Something went wrong during testing this Ticket, please try again later.');
            }
            $body = $user->name . ' has finished development of the Ticket #'.$bug->id.' "'.$bug->title.'" and now it\'s ready for test. please test if it is working well and give a feedback, either close it or choose issues found on test.';
            $this->mail('Ticket development is done!', $body, $bug->user);
            return redirect()->route('showbug',['bug' => $bug])->with('success','Ticket is waiting to be tested!');
        }else {
            abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function issueOnTest(Bug $bug)
    {
        $bug->state = 3;
        $saved = $bug->save();
        if(!$saved){
            return redirect()->back()->withInput()->with('insert_error', 'Something went wrong during saving the state "issues found" of this Ticket, please try again later.');
        }
        $user = Auth::user();
        $body = $user->name . ' has found on this Ticket #'.$bug->id.' "'.$bug->title.'". please start re-developing as soon as you can.';
        $this->mail('Issues found!', $body, User::find(5));
        return redirect()->route('showbug',['bug' => $bug])->with('success','Ticket is waiting to be re-developed!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reDevelop(Bug $bug)
    {
        $user = Auth::user();
        if ($user->developer == 1) {
            $bug->state = 4;
            $saved = $bug->save();
            if (!$saved) {
                return redirect()->back()->withInput()->with('insert_error', 'Something went wrong during re-developing this Ticket, please try again later.');
            }
            $body = $user->name . ' has finished development of the Ticket #' . $bug->id . ' "' . $bug->title . '" and now it\'s ready for test. please test if it is working well and give a feedback, either close it or choose issues found on test.';
            $this->mail('Ticket re-development is done!', $body, $bug->user);
            return redirect()->route('showbug', ['bug' => $bug])->with('success', 'Ticket is waiting to be tested!');
        } else {
            abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function finish(Bug $bug)
    {
        $bug->state = 5;
        $saved = $bug->save();
        if (!$saved) {
            return redirect()->back()->withInput()->with('insert_error', 'Something went wrong during re-developing this Ticket, please try again later.');
        }
        $user = Auth::user();
        $body = $user->name . ' has finished development of the Ticket #' . $bug->id . ' "' . $bug->title . '" and now it\'s ready for test. please test if it is working well and give a feedback, either close it or choose issues found on test.';
        $this->mail('Ticket re-development is done!', $body, $bug->user);
        return redirect()->route('showbug', ['bug' => $bug])->with('success', 'Ticket is waiting to be tested!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bug  $bug
     * @return \Illuminate\Http\Response
     */
    public function show(Bug $bug)
    {
        $data = ['bug' => $bug];
        return view('bugs.show', $data);
    }

    /**
     * Send Email.
     *
     * @param  string  $subject
     * @param  string  $body
     * @param  string  $to
     */
    public function mail($subject, $body, User $to)
    {
        return;
        Mail::send(new SendMailable($to, $subject, $body));
    }
}
