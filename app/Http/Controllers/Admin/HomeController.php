<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\UserAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\UsersModel;
use App\Models\UserHistory;
use App\Models\Organization;
use App\Models\Task_Details;
use App\Models\Task_details_history;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public $apiService;
    function login_screen()
    {
        return view('userlogin', ['Invalid' => '']);
    }

    function user_register()
    {
        return view('register', ['Invalid' => '']);
    }
    function instruction()
    {
        return view('instructions', ['Invalid' => '']);
    }
//Login for user
    function userlogin(Request $request)
    {
        $rules = [
            'email'  =>  'required',
            'password'  =>  'required',
        ];

        $customMessages = [
            'email.required' => 'The e-Mail field is required',
            'password.required' => 'The Password field is required',
        ];
        $this->validate($request, $rules, $customMessages);
        $user = UsersModel::where('email', $request->only('email'))->where('status','1')->first();
        if ($user != null && $user != "") {
            $authenticated = false;
            if (Hash::check($request->input('password'), $user->password)){
                $authenticated = true;
                $session_data = [
                    'id'            =>  $user->id,
                    'email'         =>  $user->email,
                    'password'      =>  $user->password,
                    'name'          =>  $user->name,
                    'system_role'   =>  $user->system_role,
                    'profile_path'   =>  $user->profile_path
                ];
                $request->session()->put('User_details', $session_data);
                return redirect()->away('/dashboard');
            }

            if (!$authenticated) {
                return view('userlogin', ['Invalid' => 'Username or Password Mismatch']);
            }
        }
        if ($user == "null" || $user == "") {
            return view('userlogin', ['Invalid' => 'This user (' . $request->email . ') is not recorded in system']);
        }
        return redirect()->away('/dashboard');
    }
    public function user_logout()
    {
        Session::forget('User_details');
        Session::flush();
        return view('index', ['Invalid' => '']);
    }

    public function getSessionDetail()
    {
        if (Session::get('User_details') != "") {
            return ['data' => Session::get('User_details')];
        } else {
            return redirect()->away('user_logout');
        }
    }
    //Doing Registration for new users
    public function save_new_registration(Request $request)
    {
        //validating the require fields
            $rules = [
                'name'              => 'required',
                'contact'           => 'required|numeric',
                'email'             => 'required|email',
                'password'          => 'required|min:8',
                // 'password'          => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)',
                'confirm_password'  => 'required|same:password',
            ];
            $customMessages = [
                'name.required'                 => 'Name field is required',
                'contact.required'              => 'Contact field is required',
                'email.required'                => 'Email field is required',
                'email.email'                   => 'Please enter a valid email address',
                'password.required'             => 'Password field is required',
                'password.min'                  => 'Password must be at least 8 characters long',
                'confirm_password.required'     => 'Confirm Password field is required',
                'confirm_password.same'         => 'Confirm Password must match the Password field',
            ];
            $this->validate($request, $rules, $customMessages);
            //Data preperation
            $request_data = [
                    'name'              => $request->name,
                    'phone_number'      => $request->contact,
                    'email'             => $request->email,
                    'system_role'       =>'User',
                    'password'          => Hash::make($request->password),
                    'created_at'        => date('Y-m-d h:i:s'),
                ];
            $userde = UsersModel::where('email', $request->email)->first();
            if ($userde != null && $userde != "") {
                return view('userlogin', ['Invalid' => 'This email is already registered in the system, Please login']);
            } else {
                try{
                    $response_data = UsersModel::create($request_data);
                }catch(Exception $ex){
                    return $ex;
                }
            }
            if ($response_data!=null && $response_data!='' ) {
                    $content = "Dear " . $request->name . '<br> Your registration has been successful with user name ( ' . $request->email . ') and password:  ' . $request->password
                    // <p>Your password is: ******** (hidden for security reasons).</p>
                    . '<br>. Thank you for registration.';
                    $registration_data = [
                        'email'                 =>  $request->email,
                        'subject'               =>  'New Registration',
                        'content'               =>  $content,
                        'bodyMessage'           =>  $content,
                    ];
                $mail = Mail::send('emails.registration', $registration_data, function ($message) use ($registration_data) {
                        $message->from('taskinfo41@gmail.com');
                        $message->to($registration_data['email']);
                        $message->subject($registration_data['subject']);
                    });
                // //For email break down
                // if (Mail::failures())
                //     return view('userlogin', ['Invalid' => '']);
                // } else {
                return view('userlogin', ['Invalid' => 'You have registered successfully. Please login with email as username and password you have created, both username and password are sent to your registered email']);
            }
    }
    public function create_tasks(Request $request){
            $rules = [
                'title'             =>  'required',
                'description'       =>  'required',
                'priority'          =>  'required',
                'due_date'          =>  'required',
                'taskStatus'        =>  'required',
            ];
            $customMessages = [
                'title.required'        => 'Title is required',
                'description.required'  => 'Description is required',
                'priority.required'     => 'Please select priority level',
                'due_date.required'     => 'Please select due date',
                'taskStatus.required'   => 'Please select task status',
            ];
            $this->validate($request, $rules, $customMessages);

            //data preperation for creating tasks
            $task_details = [
                'id'                    => $request->input('id'),
                'title'                 => $request->input('title'),
                'description'           => $request->input('description'),
                'priority'              => $request->input('priority'),
                'due_date'              => $request->input('due_date'),
                'taskStatus'            => $request->input('taskStatus'),
                'status'                => $request->input('status'),
                'userId'                => Session::get('User_details')['id'],
                'ownership'             => 'Own Task'
            ];
            if($request->input('action')=='add'){
                //inserting task details
                $response_data = Task_Details::create($task_details);
            }
            else{
                //updating with Id
                $response_data = Task_Details::where('id',$request->input('id'))->update($task_details);
            }
        return $response_data;
    }
    //Listing all task list
    public function tasklist(){
        $TaskDetails = Task_Details::where('UserId',Session::get('User_details')['id'])->get();
        return $TaskDetails;
    }

    //pulling assigned task lists

    public function Assigntasklist(){
        $TaskDetails = Task_details_history::where('UserId',Session::get('User_details')['id'])->get();
        return $TaskDetails;
    }
    //Fetching user details and task details by email and userId
    public function SearchUserByEmail($params){
        $paramdata = explode('__', $params);
        if($paramdata[1]=='Assign'){
            if($paramdata[0] == Session::get('User_details')['email'] ){
                return 'OwnEmail';
            }
        }
        $userDetails = UsersModel::where('email', $paramdata[0])->first();
            if($userDetails){
                //returning array inside array
                $userDetails->TaskDetails = DB::table('task_details as td')
                ->select('td.*')
                ->where('userId', $userDetails->id)
                ->get();
            return $userDetails;
            }
        return "Exist";
    }
    //Deleting the user from system
    public function DeleteUser($id){
        $user_id = $id;
        //checking for pending or under process task in system
        $checktask = Task_Details::where('userId',$user_id)->whereIn('taskStatus',['Pending','Under Process'])->first();
        if($checktask!='' && $checktask!=null){
            return 'taskExist';
        }
       //Keeping user history
        $userHistory = UsersModel::where('id',$user_id)->first();
        if($userHistory!=null && $userHistory!=''){
            $hisUser =[
                'parent_id'         =>  $userHistory->id,
                'name'              => $userHistory->name,
                'phone_number'      => $userHistory->contact,
                'email'             => $userHistory->email,
                'system_role'       => $userHistory->system_role,
                'password'          => $userHistory->password,
                'org_id'            => $userHistory->org_id,
                'position'          => $userHistory->position,
                'profile_path'      => $userHistory->profile_path,
                'created_at'        => date('Y-m-d h:i:s'),
                'created_by'        => Session::get('User_details')['id'],
                'Status'            =>'0'
            ];
            UserHistory::create($hisUser);
        }
        //deleting after keeping backup
        $updateUser = UsersModel::where('id',$user_id)->delete();
        return $updateUser;
    }
    //pulling all system users
    public function getAllSystemUsers(){
        $totalPending = 0;
        $totalCompleted = 0;
        $totalUnderProcess = 0;
        $allusers = DB::table('users as u')
        ->select('u.*','o.name as companyName')
        ->leftJoin('organization_details as o','u.org_id','=','o.id')
        ->where('u.system_role','not like','%admin%')
        ->where('u.status','1')
        ->get();
        // $allusers = UsersModel::where('status', '1')->get();
        foreach ($allusers as $user) {
            $taskcounts = DB::table('task_details')
                ->select(
                    DB::raw('COUNT(CASE WHEN taskStatus = "Pending" THEN 1 END) as pending'),
                    DB::raw('COUNT(CASE WHEN taskStatus = "Completed" THEN 1 END) as completed'),
                    DB::raw('COUNT(CASE WHEN taskStatus = "Under Process" THEN 1 END) as under_process')
                )
                ->where('userId', $user->id) // Assuming 'id' is the primary key column in the 'users' table
                ->first();

            // Assign the task counts to the user object
            $user->pending_tasks = $taskcounts->pending;
            $user->completed_tasks = $taskcounts->completed;
            $user->under_process_tasks = $taskcounts->under_process;
            
            // Update the total counts
            $totalPending += $taskcounts->pending;
            $totalCompleted += $taskcounts->completed;
            $totalUnderProcess += $taskcounts->under_process;
            $user->total = $taskcounts->pending + $taskcounts->completed +  $taskcounts->under_process;
        }
        return $allusers;
    }

    //Assigning Task
    public function Assign_task($parameters){
        //Pulling username of user who have assign you task
        $paramdata = explode('__', $parameters);
        $PullingUserId = Task_Details::where('id',$paramdata[0])->first();
        if($PullingUserId){
            $username = UsersModel::select('name','email')->where('id',$PullingUserId->userId)->first();
        }
        $newuser = [
            'userId'     => $paramdata[1],
            'ownership'  => 'Assigned by ' . $username->email,
        ];
        $TaskDetails = Task_Details::where('id',$paramdata[0])->update($newuser);

        //deleting for previous data
        if($paramdata[3]=='Re_assign'){
            $TaskDetails = Task_details_history::where('id',$paramdata[4])->delete();
                $content = "Dear " . $paramdata[5] . '<br> You have got new task assigned by ( ' .$username->email .'), Please login into the system and  check the deatils : ' 
                . '<br>';
        }
        else{
            $content = "Dear " . $paramdata[4] . '<br> You have got new task assigned by ( ' .$username->email .'), Please login into the system and  check the deatils : ' 
            . '<br>';
        }
        //Keeping history
        $task_history = [
            'parent_id'             => $PullingUserId->id,
            'title'                 => $PullingUserId->title,
            'description'           => $PullingUserId->description,
            'priority'              => $PullingUserId->priority,
            'due_date'              => $PullingUserId->due_date,
            'taskStatus'            => $PullingUserId->taskStatus,
            'status'                => $PullingUserId->status,
            'userId'                => Session::get('User_details')['id'],
            'ownership'             => 'Assigned to ' . $paramdata[2],
        ];
        $TaskDetails = Task_details_history::create($task_history);

        //Sending email notification
        if($TaskDetails){
            $task_notification = [
                'email'                 =>  $paramdata[2],
                'subject'               =>  'Task Assigned',
                'content'               =>  $content,
                'bodyMessage'           =>  $content,
            ];
            $mail = Mail::send('emails.mail', $task_notification, function ($message) use ($task_notification) {
                $message->from('taskinfo41@gmail.com');
                $message->to($task_notification['email']);
                $message->subject($task_notification['subject']);
            });
            if (Mail::failures())
                return json_encode('email failed');
        }
        return $TaskDetails;
    }

    //listing task by params
    public function tasklistByParams($params){
        //spliting the parameters using explode
        $paramdata = explode('__', $params);
        if($paramdata[0]=='Under_Process'){
            $paramdata[0] = 'Under Process';
        }
        $TaskDetails=[];
        if($paramdata[0]!='' && $paramdata[1]==''){
            $TaskDetails = Task_Details::where('UserId',Session::get('User_details')['id'])
            ->where('taskStatus',$paramdata[0])->get();
        }
        else if($paramdata[0]=='' && $paramdata[1]!='' ){
            $TaskDetails = Task_Details::where('UserId',Session::get('User_details')['id'])
            ->where('priority',$paramdata[1])->get();
        }
        else if($paramdata[0]!='' && $paramdata[1]!='' ){
            $TaskDetails = Task_Details::where('UserId',Session::get('User_details')['id'])
            ->where('priority',$paramdata[1])->where('taskStatus',$paramdata[0])->get();
        }
        return $TaskDetails;
        
    }
    //Deleting the task re-assigning
    public function taskDeleteReAssigned($id){
        $deletingReAssignTask = Task_details_history::where('parent_id',$id)->delete();
        if($deletingReAssignTask){
            $deletingTask = Task_Details::where('id',$id)->delete();
        }
        return $deletingReAssignTask;

    }
    //Deleting Task
    public function taskDelete($id=""){
        $deletingTask = Task_Details::where('id',$id)->delete();
        return $deletingTask;
    }
    //Function to update profile details
    public function UpdatePerDetails(Request $request){
        if($request->imageNew==true){
            $validatedData = $request->validate([
                'profilePicture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
            $file = $request->file('profilePicture');
            $path = "";
            $file_store_path = 'userProfile';
            
            if ($file !== null && $file->isValid()) {
                $existingFile = "public/" . $request->profile_path;
                if (Storage::exists($existingFile)) {
                    Storage::delete($existingFile);
                }
                $file_name = time() . '_' . $file->getClientOriginalName();
                $file_path = $file->storeAs($file_store_path, $file_name, 'public');
                $path = $file_store_path . '/' . $file_name;
            }
        }else{
            $path = $request->profilePicture;
        }
        
        //prepering data for inserting
        $profileDetails = [
            'name'          => $request->input('name'),
            'email'         => $request->input('email'),
            'phone_number'  => $request->input('contact_no'),
            'position'      => $request->input('position'),
            'org_id'        => $request->input('org_id'),
            'profile_path'  => $path,
        ];
        $user = UsersModel::find(Session::get('User_details')['id']);
        if($user) {
            $user->update($profileDetails);
        }
        return $user;
    }
    public function getPersonalDetails($id){
        $user_details = UsersModel::where('id',Session::get('User_details')['id'])->first();
        if($user_details){  
            $user_details->taskDetails = Task_Details::where('userId',$id)->get();
        };
        return $user_details;

    }
    function change_password(Request $request)
    {
        try {
            $db_userdetails = DB::table('users')->where('id', Session::get('User_details')['id'])->first();
            if (Hash::check($request->current_password, $db_userdetails->password)) {
                $password_details = array(
                    'password' => Hash::make($request->confirm_password)
                );
                DB::table('users')->where('id', Session::get('User_details')['id'])->update($password_details);
            } 
            //sending details in mail 
            if($db_userdetails){
                $content = "Dear " . Session::get('User_details')['name'] . '<br> You have changed your password from ( ' .$request->current_password .') to ( ' .$request->confirm_password .') ' 
                // <p>Your password is: ******** (hidden for security reasons).</p>
            . '<br>';
                $passwordchange = [
                    'email'                 =>   Session::get('User_details')['email'],
                    'subject'               =>  'Password changed',
                    'content'               =>  $content,
                    'bodyMessage'           =>  $content,
                ];
                $mail = Mail::send('emails.passwordMail', $passwordchange, function ($message) use ($passwordchange) {
                    $message->from('taskinfo41@gmail.com');
                    $message->to($passwordchange['email']);
                    $message->subject($passwordchange['subject']);
                });
                if (Mail::failures())
                    return json_encode('email failed');
                }
            
        } catch(\Exception $e) {
            return json_encode('Exception: ' . $e);
        } 
    }
    public function taskDetails(){
        if(Session::get('User_details')['system_role'] == 'User'){
            $taskSummary = Task_Details::where('userId',Session::get('User_details')['id'])->first();
            if($taskSummary){
                $counts = DB::table('task_details')
                    ->select(
                        DB::raw('COUNT(CASE WHEN taskStatus = "Pending" THEN 1 END) as pending'),
                        DB::raw('COUNT(CASE WHEN taskStatus = "Completed" THEN 1 END) as completed'),
                        DB::raw('COUNT(CASE WHEN taskStatus = "Under Process" THEN 1 END) as under_process')
                    )
                    ->where('userId',Session::get('User_details')['id'])
                    // ->groupBy('taskStatus')
                    ->first();
            }
        }
        else{
            $counts = DB::table('task_details')
                    ->select(
                        DB::raw('COUNT(CASE WHEN taskStatus = "Pending" THEN 1 END) as pending'),
                        DB::raw('COUNT(CASE WHEN taskStatus = "Completed" THEN 1 END) as completed'),
                        DB::raw('COUNT(CASE WHEN taskStatus = "Under Process" THEN 1 END) as under_process')
                    )
                    // ->groupBy('taskStatus')
                    ->first();
        }
        return $counts;

    }
    public function change_password_for_user(Request $request){
        //checking the entered email is registrered in the system or not
        $emailchecking = UsersModel::where('email',$request->email)->first();
        if($emailchecking){ 
            $password_reset_details = array(
                'password' => Hash::make($request->confirm_password)
            );
            $password_reset_details =DB::table('users')->where('email', $request->email)->update($password_reset_details);

            //sending mail to user for information
            if ($password_reset_details) {
                $content = "Your password has been reset by administrator to  $request->confirm_password for email $request->email in Task Management system ,<b> Please login with new password";
                // <p>Your password is: ******** (hidden for security reasons).</p>
                $email = $request->email;
                    $passwordreset = [
                        'email'                 =>   $email,
                        'subject'               =>  'Password Reset',
                        'content'               =>  $content,
                        'bodyMessage'           =>  $content,
                    ];
                    $mail = Mail::send('emails.passwordMail', $passwordreset, function ($message) use ($passwordreset) {
                        $message->from('taskinfo41@gmail.com');
                        $message->to($passwordreset['email']);
                        $message->subject($passwordreset['subject']);
                    });
                    if (Mail::failures())
                        return json_encode('email failed');
                    }
                return '';
        }
        return 'Null';
    }

    //adding organization master
    public function add_organization(Request $request){
    //validating the fields
        $rules = [
            'name'          =>  'required',
            'org_code'      =>  'required',
            'description'   =>  'required',
        ];

        $customMessages = [
            'name.required'         => 'The name field is required',
            'org_code.required'     => 'Oranization code is required',
            'description.required'  => 'Description required',
        ];
        $this->validate($request, $rules, $customMessages);  
        //data preperation
            $request_data = [
                'id'                => $request->id,
                'name'              => $request->name,
                'org_code'          => $request->org_code,
                'description'       => $request->description,
                'created_by'        => Session::get('User_details')['id'],
                'created_at'        => date('Y-m-d h:i:s'),
            ];
            //inserting/updating the data
            if($request->action=='add'){
                $insert_data = Organization::create($request_data);
            }
            else{
                $insert_data = Organization::where('id',$request->id)->update($request_data);
            }
            return $insert_data;
    }
    //Listing all organization
    public function orgListDetails(){
        $organization_list = Organization::get();
    return $organization_list;
    }

    // deleting organization by id
    public function OrgDelete($id){
    // checking if active user is mapped to this organization
    $checkingUser = UsersModel::where('org_id',$id)->where('status','1')->get();
    if( sizeof($checkingUser)){
            return 'Active_user';
        }   
            $delete_org = Organization::where('id',$id)->delete();
            return $delete_org;
    }

//pulling notification
    public function getNotification(){
        $counts = DB::table('task_details')
        ->select(
            DB::raw('COUNT(*) as notificationCount'),
        )
        ->where('UserId',Session::get('User_details')['id'])
        ->whereIn('taskStatus',['Pending','Under Process'])
        ->where('ownership','like','%Assigned by%')
        ->first();
    return $counts;
    }
//checking email in the system
    public function emailchecking($email){
        $check = false;
        $emailchecking = UsersModel::where('email',$email)->first();
        if($emailchecking!=null && $emailchecking!=''){
            $check=true;
        }
        return $check;
    }
    public function add_newuser(Request $request){
        //validating the fields
        $rules = [
            'email'             =>  'required',
            'name'              =>  'required',
            'contact_no'        =>  'required|numeric',
            'password'          =>  'required|min:8',
            'confirm_password'  =>  'required|same:password',
            'role'              =>  'required',
            'org_id'            =>  'required',
        ];

        $customMessages = [
            'name.required'                 => 'Name field is required',
            'contact_no.required'              => 'Contact field is required',
            'role.required'                 => 'Please select system role',
            'org_id.required'               => 'Please select company',
            'email.required'                => 'Email field is required',
            'email.email'                   => 'Please enter a valid email address',
            'password.required'             => 'Password field is required',
            'password.min'                  => 'Password must be at least 8 characters long',
            'confirm_password.required'     => 'Confirm Password field is required',
            'confirm_password.same'         => 'Confirm Password must match the Password field',
        ];
        $this->validate($request, $rules, $customMessages);

        //checking duplicate email enter from backend
        $emailchecking = UsersModel::where('email',$request->email)->first();
        if($emailchecking!=null && $emailchecking!=''){
            return 'exist';
        }
        // data preperation
        $user_data = [
            'id'                => $request->id,
            'name'              => $request->name,
            'org_id'            => $request->org_id,
            'email'             => $request->email,
            'system_role'       => $request->role,
            'phone_number'      => $request->contact_no,
            'password'          => Hash::make($request->password),
            'created_by'        => Session::get('User_details')['id'],
            'created_at'        => date('Y-m-d h:i:s'),
            'Status'            => '1',
        ];
        $emailchecking = UsersModel::create($user_data);
        //aftering storing, now sending mail to user
        if($emailchecking){
            $content = "Dear " . $request->name . ',<br><br>'
            . 'Congratulations! Your registration has been successfully completed. Your account details are as follows:<br><br>'
            . 'Username: ' . $request->email . '<br>'
            . 'Password: ' . $request->password . '<br>'
            . 'Thank you for registering. If you have any questions or need further assistance, please feel free to contact us.<br><br>'
            
            . 'Best regards,<br>'
            . 'Admin';

                $registration_data = [
                    'email'                 =>  $request->email,
                    'subject'               =>  'New Registration',
                    'content'               =>  $content,
                    'bodyMessage'           =>  $content,
                ];
            $mail = Mail::send('emails.registration', $registration_data, function ($message) use ($registration_data) {
                $message->from('taskinfo41@gmail.com');
                $message->to($registration_data['email']);
                $message->subject($registration_data['subject']);
            });
        }
        // //For email break down
        if (Mail::failures()){
            return view('userlogin', ['Invalid' => '']);
        }

    }

}

