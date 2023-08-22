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
use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use App\Models\Invoice_history;
use App\Models\ApplicationSequence;
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
                        'subject'               =>  'User Registration',
                        'content'               =>  $content,
                        'bodyMessage'           =>  $content,
                    ];
                $mail = Mail::send('emails.registration', $registration_data, function ($message) use ($registration_data) {
                        $message->from('easybillingsystem.net@gmail.com');
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
    //method to create purchase order
    public function create_purposeOrder(Request $request){
        $rules = [
            'addressTo'                 =>  'required',
            'order_date'                =>  'required',
            'end_user'                  =>  'required',
            'unit'                      =>  'required',
            'termAndCondition'          =>  'required',
        ];
        $customMessages = [
            'addressTo.required'        => 'Address to is required',
            'end_user.required'         => 'End user details  is required',
            'order_date.required'       => 'Please select purchase order date',
            'unit.required'             => 'Please select unit',
            'termAndCondition.required' => 'Please select unit',
        ];
        $this->validate($request, $rules, $customMessages);
        //generating application number
        if($request->input('action')=='add'){
                $last_seq=ApplicationSequence::where('service_name','purchase_order')->where('year', date('Y'))->first();
                if($last_seq==null || $last_seq==""){
                    $last_seq=1;
                    $app_details = [
                        'service_name'                  =>  'purchase_order',
                        'last_sequence'                 =>  $last_seq,
                        'year'                          =>  date('Y'),
                    ];
                    ApplicationSequence::create($app_details);
                }
                else{
                    $last_seq=$last_seq->last_sequence+1;
                    $app_details = [
                        'last_sequence'                 =>  $last_seq,
                    ];
                    ApplicationSequence::where('service_name', 'purchase_order')->update($app_details);
                }

                $application_no='NET/Purchase/';
                if(strlen($last_seq)==1){
                    $application_no= $application_no.date('Y').'/000'.$last_seq;
                }
                else if(strlen($last_seq)==2){
                    $application_no= $application_no.date('Y').'/00'.$last_seq;
                }
                else if(strlen($last_seq)==3){
                    $application_no= $application_no.date('Y').'/0'.$last_seq;
                }
                else if(strlen($last_seq)==4){
                    $application_no= $application_no.date('Y').'/'.$last_seq;
                }
            }
        //data preperation for creating tasks
            $Purchase = [
                'id'                    => $request->input('id'),
                'order_date'            => $request->input('order_date'),
                'end_user'              => $request->input('end_user'),
                'addressTo'             => $request->input('addressTo'),
                'termAndCondition'      => $request->input('termAndCondition'),
                'totalAmount'           => $request->input('totalAmount'),
                'totalAmountInWords'    => $request->input('totalAmountInWords'),
                'unit'                  => $request->input('unit'),
                'status'                => 'Pending',
                'academic_year'         => date('Y'),
                'userId'                => Session::get('User_details')['id'],
                'created_by'            => Session::get('User_details')['id'],
                'created_at'            =>  date('Y-m-d h:i:s')
            ];
        if($request->input('action')=='add'){
            $Purchase = $Purchase + [
                'refer_no'               => $application_no,
            ];
            $response_data = Purchase::create($Purchase);
            foreach ($request->item_list as $i=> $item){
                $Purchase_details = array(
                    'description'           =>  $item['description'],
                    'purchaseId'            =>  $response_data->id,
                    'qty'                   =>  $item['qty'],
                    'rate'                  =>  $item['rate'],
                    'part_number'           =>  $item['part_number'],
                    'duration'              =>  $item['duration'],
                    'amount'                =>  $item['amount'],
                    'created_by'            =>  Session::get('User_details')['id'],
                    'created_at'            =>  date('Y-m-d h:i:s')
                );
                PurchaseDetails::create($Purchase_details);
            }
        }

        else{
            $Purchase = $Purchase + [
                'refer_no'               => $request->input('refer_no'),
            ];
            //updating with Id
            $response_data = Purchase::where('id',$request->input('id'))->update($Purchase);
            $response_data = PurchaseDetails::where('purchaseId',$request->input('id'))->delete();
            foreach ($request->item_list as $i=> $item){
                $Purchase_details = array(
                    'description'           =>  $item['description'],
                    'purchaseId'            =>  $request->input('id'),
                    'qty'                   =>  $item['qty'],
                    'rate'                  =>  $item['rate'],
                    'part_number'           =>  $item['part_number'],
                    'duration'              =>  $item['duration'],
                    'amount'                =>  $item['amount'],
                    'created_by'            =>  Session::get('User_details')['id'],
                    'created_at'            =>  date('Y-m-d h:i:s')
                );
                PurchaseDetails::create($Purchase_details);
            }
        }
    return $response_data;
    }
    //method to create invoice
    public function create_invoice(Request $request){
            $rules = [
                'due_date'                  =>  'required',
                'addressTo'                 =>  'required',
                'entry_date'                =>  'required',
                'workorderNo'               =>  'required',
            ];
            $customMessages = [
                'addressTo.required'        => 'Address To is required',
                'workorderNo.required'      => 'Work Order No is required',
                'due_date.required'         => 'Please select Due Date',
                'entry_date.required'       => 'Please select Entry Date',
            ];
            $this->validate($request, $rules, $customMessages);
            //generating application number
            if($request->input('action')=='add'){
                    $last_seq=ApplicationSequence::where('service_name','invoice')->where('year', date('Y'))->first();
                    if($last_seq==null || $last_seq==""){
                        $last_seq=1;
                        $app_details = [
                            'service_name'                  =>  'invoice',
                            'last_sequence'                 =>  $last_seq,
                            'year'                          =>  date('Y'),
                        ];
                        ApplicationSequence::create($app_details);
                    }
                    else{
                        $last_seq=$last_seq->last_sequence+1;
                        $app_details = [
                            'last_sequence'                 =>  $last_seq,
                        ];
                        ApplicationSequence::where('service_name', 'invoice')->update($app_details);
                    }

                    $application_no='NET/Invoice/';
                    if(strlen($last_seq)==1){
                        $application_no= $application_no.date('Y').'/000'.$last_seq;
                    }
                    else if(strlen($last_seq)==2){
                        $application_no= $application_no.date('Y').'/00'.$last_seq;
                    }
                    else if(strlen($last_seq)==3){
                        $application_no= $application_no.date('Y').'/0'.$last_seq;
                    }
                    else if(strlen($last_seq)==4){
                        $application_no= $application_no.date('Y').'/'.$last_seq;
                    }
                }
            

            //data preperation for creating tasks
                $Invoice = [
                    'id'                    => $request->input('id'),
                    'entry_date'            => date('Y-m-d'),
                    'due_date'              => $request->input('due_date'),
                    'addressTo'             => $request->input('addressTo'),
                    'workorderNo'           => $request->input('workorderNo'),
                    'totalAmount'           => $request->input('totalAmount'),
                    'totalAmountInWords'    => $request->input('totalAmountInWords'),
                    'Tpn_no'                => 'NAC00078',
                    'status'                => 'Pending',
                    'academic_year'         => date('Y'),
                    // 'Bill_no'               => $application_no,
                    'userId'                => Session::get('User_details')['id'],
                    'created_by'            => Session::get('User_details')['id'],
                    'created_at'            =>  date('Y-m-d h:i:s')
                ];
            if($request->input('action')=='add'){
                $Invoice = $Invoice + [
                    'Bill_no'               => $application_no,
                ];
                $response_data = Invoice::create($Invoice);
                foreach ($request->item_list as $i=> $item){
                    $invoice_details = array(
                        'particular'            =>  $item['particular'],
                        'invoiceId'             =>  $response_data->id,
                        'qty'                   =>  $item['qty'],
                        'rate'                  =>  $item['rate'],
                        'amount'                =>  $item['amount'],
                        'created_by'            =>  Session::get('User_details')['id'],
                        'created_at'            =>  date('Y-m-d h:i:s')
                    );
                    InvoiceDetails::create($invoice_details);
                }
            }

            else{
                $Invoice = $Invoice + [
                    'Bill_no'               => $request->input('Bill_no'),
                ];
                //updating with Id
                $response_data = Invoice::where('id',$request->input('id'))->update($Invoice);
                $response_data = InvoiceDetails::where('invoiceId',$request->input('id'))->delete();
                foreach ($request->item_list as $i=> $item){
                    $invoice_details = array(
                        'particular'            =>  $item['particular'],
                        'invoiceId'             =>  $request->input('id'),
                        'qty'                   =>  $item['qty'],
                        'rate'                  =>  $item['rate'],
                        'amount'                =>  $item['amount'],
                        'created_by'            =>  Session::get('User_details')['id'],
                        'created_at'            =>  date('Y-m-d h:i:s')
                    );
                    InvoiceDetails::create($invoice_details);
                }
            }
        return $response_data;
    }
    //Listing all task list
    public function InvoiceList(){
            $InvoiceDetails = DB::table('Invoice as iv')
                ->join('users as u','iv.UserId','=','u.id')
                ->select('iv.*','u.name','u.system_role')
                ->where('iv.status', 'Pending')
                ->get();
        return $InvoiceDetails;
    }
    public function PurchaseList(){
        $PurchaseList = DB::table('purchase as p')
        ->join('users as u','p.UserId','=','u.id')
        ->select('p.*','u.name','u.system_role')
        ->where('p.status', 'Pending')
        ->get();
    return $PurchaseList;
    }

    public function BillList(){
        $year = date('Y'); // Get the current year
        $month = date('m'); // Get the current month
        $BillList = DB::table('Invoice as iv')
        ->leftjoin('users as u','iv.reference_byId','=','u.id')
        ->select('iv.*','u.name as recieved_by')
        ->whereRaw('YEAR(entry_date) = ? AND MONTH(entry_date) = ?', [$year, $month])
        ->orderBy('entry_date', 'desc')
        ->get();
    
        return $BillList;
    }

    public function PurchaseOrderList(){
        $year = date('Y'); // Get the current year
        $month = date('m'); // Get the current month
        $PurchaseOrderList = DB::table('purchase as o')
        ->leftjoin('users as u','o.receivedBy_id','=','u.id')
        ->select('o.*','u.name as recieved_by')
        ->whereRaw('YEAR(order_date) = ? AND MONTH(order_date) = ?', [$year, $month])
        ->orderBy('order_date', 'desc')
        ->get();
        
        return $PurchaseOrderList;
    }

    //pulling assigned task lists

    public function InvoiceDetails($id){
        $InvoiceDetails = InvoiceDetails::where('invoiceId',$id)->get();
        return $InvoiceDetails;
    }
    public function PurchaseDetails($id){
        $PurchaseDetails = PurchaseDetails::where('purchaseId',$id)->get();
        return $PurchaseDetails;
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
                $userDetails->TaskDetails = DB::table('Invoice as td')
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
        $allusers = DB::table('users as u')
        ->select('u.*','o.name as companyName')
        ->leftJoin('organization_details as o','u.org_id','=','o.id')
        ->where('u.system_role','not like','%admin%')
        ->where('u.status','1')
        ->get();
        return $allusers;
    }


    //listing task by params
    public function BillListByParams($params){
        //spliting the parameters using explode
        $paramdata = explode('__', $params);
        if($paramdata[0]=='Not_Received'){
            $paramdata[0] = 'Pending';
        }
        $billList=[];
        $userId = session('User_details')['id'];
        $fromDate = $paramdata[1];
        $toDate = $paramdata[2];
        if($paramdata[0]!='All'){
            $billList = Invoice::where('UserId', $userId)
                ->where('status', $paramdata[0])
                ->where('entry_date', '>=', $fromDate)
                ->where('entry_date', '<=', $toDate)
                ->get();
        }
        else{
            $billList = Invoice::where('UserId', $userId)
            ->where('entry_date', '>=', $fromDate)
            ->where('entry_date', '<=', $toDate)
            ->get();
        }
        return $billList;
        
    }

    //pulling purchase order by parameter
    public function PurchaseOrderListByParams($params){
        $paramdata = explode('__', $params);
        if($paramdata[0]=='Not_Received'){
            $paramdata[0] = 'Pending';
        }
        $purchaseOrderList=[];
        $fromDate = $paramdata[1];
        $toDate = $paramdata[2];
        if($paramdata[0]!='All'){
            $purchaseOrderList = Purchase::where('status', $paramdata[0])
                ->where('order_date', '>=', $fromDate)
                ->where('order_date', '<=', $toDate)
                ->get();
        }
        else{
            $purchaseOrderList = Purchase::where('order_date', '>=', $fromDate)
            ->where('order_date', '<=', $toDate)
            ->get();
        }
        return $purchaseOrderList;
    }
    public function InvoiceDelete($id){
        $Delete = Invoice::where('id',$id)->delete();
            InvoiceDetails::where('invoiceId',$id)->delete();
        return $Delete;
    }
    //function to delete purchase order
    public function DeletePurchase($id){
        $Delete = Purchase::where('id',$id)->delete();
            PurchaseDetails::where('purchaseId',$id)->delete();
        return $Delete;
    }

    //Updating bill
    public function Received($id=""){
        $data = [
            'status'        => 'Received',
        ];
        $Received = Invoice::where('id',$id)->update($data);
        return $Received;
    }
    public function uploadBillDoc(Request $request){
        //inserting file path
        $file = $request->file;
        if($file){
            if ($file != "undefined" && isset($request->existing_attachments) && $request->existing_attachments != null) {
                $path = $request->existing_attachments;
                unlink($path); //delete file and replace path from followng code
            }
            $path = '';
                $file_store_path = config('services.constant.file_stored_base_path') . 'BillFile';
                if ($file != null && $file != "" && $file != "undefined") {
                    if (!is_dir($file_store_path)) {
                        mkdir($file_store_path, 0777, TRUE);
                    }
                    $file_name = time() . '_' . $file->getClientOriginalName();
                    move_uploaded_file($file, $file_store_path . '/' . $file_name);
                    $path = $file_store_path . '/' . $file_name;
        }
         //pulling total amount to minus from tds
            $total_amount = Invoice::select('totalAmount')->where('id', $request->id)->first();
            if ($total_amount) {
                $amount_received = $total_amount->totalAmount - $request->tds;
            } else {
                // Handle the case where no invoice is found for the given ID
                // For example, you can set a default value for $amount_received or show an error message.
            }
            // generating ref number
                $last_seq=ApplicationSequence::where('service_name','Receipt')->where('year', date('Y'))->first();
                    if($last_seq==null || $last_seq==""){
                        $last_seq=1;
                        $app_details = [
                            'service_name'                  =>  'Receipt',
                            'last_sequence'                 =>  $last_seq,
                            'year'                          =>  date('Y'),
                        ];
                        ApplicationSequence::create($app_details);
                    }
                    else{
                        $last_seq=$last_seq->last_sequence+1;
                        $app_details = [
                            'last_sequence'                 =>  $last_seq,
                        ];
                        ApplicationSequence::where('service_name', 'Receipt')->update($app_details);
                    }

                    $application_no='NET/Receipt/';
                    if(strlen($last_seq)==1){
                        $application_no= $application_no.date('Y').'/000'.$last_seq;
                    }
                    else if(strlen($last_seq)==2){
                        $application_no= $application_no.date('Y').'/00'.$last_seq;
                    }
                    else if(strlen($last_seq)==3){
                        $application_no= $application_no.date('Y').'/0'.$last_seq;
                    }
                    else if(strlen($last_seq)==4){
                        $application_no= $application_no.date('Y').'/'.$last_seq;
                    }
                
        //updating document path by id
            $update_file = [
                'bill_file'         => $path,
                'received_date'     => $request->dateOfRecieved,
                'tds'               => $request->tds,
                'Receipt_no'        => $application_no,
                'amount_received'   => $amount_received,
                'reference_number'  => $request->refNumber,
                'reference_byId'    => Session::get('User_details')['id'],
            ];
            // Assuming 'Invoice' is the model for the database table
            $UploadFile = Invoice::where('id', $request->id)->update($update_file);
        return $UploadFile;
        }
    }

    //function to update file in purchase order 
    public function uploadPurchaseOrderDoc(Request $request){
        //inserting file path
        $file = $request->file;
        if($file){
            if ($file != "undefined" && isset($request->existing_attachments) && $request->existing_attachments != null) {
                $path = $request->existing_attachments;
                unlink($path); //delete file and replace path from followng code
            }
            $path = '';
                $file_store_path = config('services.constant.file_stored_base_path') . 'purchase_order';
                if ($file != null && $file != "" && $file != "undefined") {
                    if (!is_dir($file_store_path)) {
                        mkdir($file_store_path, 0777, TRUE);
                    }
                    $file_name = time() . '_' . $file->getClientOriginalName();
                    move_uploaded_file($file, $file_store_path . '/' . $file_name);
                    $path = $file_store_path . '/' . $file_name;
        }
        //updating document path by id
            $update_file = [
                'purchase_file'     => $path,
                'received_date'     => $request->dateOfRecieved,
                'status'            => 'Received',
                'receivedBy_id'     => Session::get('User_details')['id'],
            ];
            // Assuming 'Purchase' is the model for the database table
            $UploadFile = Purchase::where('id', $request->id)->update($update_file);
        return $UploadFile;
        }
    }
    //Function to update profile details

    public function viewFiles($path){
        $full_path = str_replace('SSS', '/', $path);
        return response()->download($full_path);
    }
    public function UpdatePerDetails(Request $request){
        if($request->imageNew==true){
            $validatedData = $request->validate([
                'profilePicture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
            $file = $request->file('profilePicture');
            $path = "";
            $file_store_path = 'profile';
            
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
            $user_details->taskDetails = Invoice::where('userId',$id)->get();
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
                    $message->from('easybillingsystem.net@gmail.com');
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
    //Pulling and displaying summary data in dashboard
    public function DashboardDetails(){
        $year = date('Y');
        $userId = Session::get('User_details')['id'];
        $userRole = Session::get('User_details')['system_role'];
        
        $query = DB::table('Invoice')
            ->select(
                DB::raw('COUNT(CASE WHEN status = "Pending" THEN 1 END) as pending'),
                DB::raw('COUNT(CASE WHEN status = "Received" THEN 1 END) as Received'),
                DB::raw('COUNT(CASE WHEN academic_year = ' . $year . ' THEN 1 END) as total_bills')
            );
        if ($userRole == 'User') {
            $query->where('userId', $userId);
        } 
        
        $counts = $query->first();
        return $counts;
        

    }
        //Pulling and displaying summary data in graph
        public function fetchDashboardDetailsForGraph(){
            $year = date('Y');
            $month = date('m'); // Get the current month
            $userId = Session::get('User_details')['id'];
            $userRole = Session::get('User_details')['system_role'];
            $pending_bill = DB::table('Invoice')
                            ->select('due_date','addressTo')
                            ->where('status','Pending')
                            ->whereRaw('academic_year = ? AND MONTH(due_date) = ?', [$year, $month])
                            ->get();
            
            return $pending_bill;
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
                $content = "Your password has been reset by administrator to  $request->confirm_password for email $request->email in NET Billing System ,<b> Please login with new password";
                // <p>Your password is: ******** (hidden for security reasons).</p>
                $email = $request->email;
                    $passwordreset = [
                        'email'                 =>   $email,
                        'subject'               =>  'Password Reset',
                        'content'               =>  $content,
                        'bodyMessage'           =>  $content,
                    ];
                    $mail = Mail::send('emails.passwordMail', $passwordreset, function ($message) use ($passwordreset) {
                        $message->from('easybillingsystem.net@gmail.com');
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

//pulling notification
    public function getNotification(){
        $counts = DB::table('Invoice')
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
        ];

        $customMessages = [
            'name.required'                 => 'Name field is required',
            'contact_no.required'              => 'Contact field is required',
            'role.required'                 => 'Please select system role',
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
                $message->from('easybillingsystem.net@gmail.com');
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

