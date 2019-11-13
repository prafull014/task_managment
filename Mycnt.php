<?php

class mycnt extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		$this->load->model('mydb');
        }
// -----------------------------------------------
	public function index(){
	$this->form_validation('U');
    if ($this->form_validation->run())
     {
    	$email = $this->input->post('email');
    	$password = md5($this->input->post('password'));

        $admin = $this->mydb->login($email,$password);
  
        if (isset($admin->email)&&$admin->email==true && $admin->type=='admin') {
        	$this->session->set_flashdata('success','Welcome admin !');        	
             $this->session->set_userdata('admin_id',$admin);
             redirect ('mycnt/admin_panel');
        }
        elseif (isset($admin->email)&&$admin->email==true && $admin->type=='user' && $admin->status=='1') {
        	$this->session->set_flashdata('success','You loged in succesfully !');
             $this->session->set_userdata('user_id',$admin);
              redirect ('mycnt/user');
        }
        else
        {
          $this->session->set_flashdata('error','You are not registered user');
        }
     }

		  $this->load->view('user/login');
	}
//---------------------------------------------------------
// ---------------------------------------------------------
	public function form_validation($type){
		if($type=='R')
		{

        $this->form_validation->set_rules('name','Name','trim|required|alpha');
        $this->form_validation->set_rules('email','Email','trim|required|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('password','Password','trim|required|alpha_numeric|max_length[5]|min_length[5]');
        $this->form_validation->set_rules('confirm_Password','Confirm Password','trim|required|alpha_numeric|max_length[5]|min_length[5]|matches[password]');        
        $this->form_validation->set_rules('contact','Contact','trim|required|numeric|min_length[10]|max_length[13]|is_unique[user.contact]');
        $this->form_validation->set_rules('gender','Gender','required');
        $this->form_validation->set_rules('status','Status','required');        
        $this->form_validation->set_message('is_unique','already exixst try another');
        $this->form_validation->set_error_delimiters("<p class='text-danger'>","</p>"); 
        
        $config=['upload_path'        =>  'upload',
                 'allowed_types'      =>  'gif|jpg|png|JPEG'
                ];
           $this->load->library('upload',$config);
           }
           if ($type=='U'){
                  $this->form_validation->set_rules('email','Email','trim|required|valid_email');
                  $this->form_validation->set_rules('password','Password','trim|required|alpha_numeric|max_length[5]|min_length[5]');
                  $this->form_validation->set_error_delimiters('<span style="color:red">','</span>');                       
            }          
            if ($type=='A'){
             $this->form_validation->set_rules('email','Email','trim|required|valid_email|is_unique[user.email]');
             $this->form_validation->set_rules('pass','Password','trim|required|alpha_numeric|max_length[5]|min_length[5]');
             $this->form_validation->set_rules('confi_pass','Confirm_Password','trim|required|alpha_numeric|max_length[5]|min_length[5]|matches[pass]');
             $this->form_validation->set_error_delimiters("<p class='text-danger'>","</p>");  
              $config=['upload_path'        =>  'upload',
                 'allowed_types'      =>  'gif|jpg|png|JPEG'
                ];
               $this->load->library('upload',$config);	          
            }
            if ($type=='C'){
           $this->form_validation->set_rules('old',   'Old Password','required|callback_check_password');
           $this->form_validation->set_rules('new',   'NEW Password','trim|required|alpha_numeric|max_length[5]|min_length[5]');
           $this->form_validation->set_rules('confi','Confirm Password','trim|required|alpha_numeric|max_length[5]|min_length[5]|matches[new]');
           $this->form_validation->set_error_delimiters("<p class='text-danger'>","</p>");
            }
            if ($type=='T') 
            {
	           $this->form_validation->set_rules('title',   'Title','required');
	           $this->form_validation->set_rules('desc',   'Description','required');
	           $this->form_validation->set_rules('user',   'User','required');
	           $this->form_validation->set_rules('task_status',   'Status','required');                      	
               $this->form_validation->set_error_delimiters("<p class='text-danger'>","</p>");            
               $config=['upload_path'        =>  'upload',
                 'allowed_types'      =>  'gif|jpg|png|JPEG|pdf'
                ];
               $this->load->library('upload',$config);	
            }
            if ($type=='ET') 
            {
	           $this->form_validation->set_rules('title',   'Title','required');
	           $this->form_validation->set_rules('desc',   'Description','required');
	           $this->form_validation->set_rules('task_status',   'Status','required');                      	
               $this->form_validation->set_error_delimiters("<p class='text-danger'>","</p>");
             
               $config=['upload_path'        =>  'upload',
                 'allowed_types'      =>  'gif|jpg|png|JPEG'
                ];
               $this->load->library('upload',$config);

            }
            if ($type=='E') 
            {
            	$this->form_validation->set_rules('name','Name','trim|required|alpha');
		        $this->form_validation->set_rules('email','Email','trim|required|valid_email');
		        $this->form_validation->set_rules('contact','Contact','trim|required|numeric|min_length[10]|max_length[13]');
		        $this->form_validation->set_rules('gender','Gender','required');
	            $this->form_validation->set_error_delimiters("<p class='text-danger'>","</p>");

               $config=['upload_path'        =>  'upload',
                 'allowed_types'      =>  'gif|jpg|png|JPEG|pdf|html'
                ];
               $this->load->library('upload',$config);
	        }
           return $this->form_validation->run();
        }
//-------------------------------------------------------------------
    public function user(){
    	// $this->loged_in('U');
    	$this->load->view('user/profile');
    } 
   //-----------------------------------------
   public function add_user()
   {
    	   // $this->loged_in('A');        	                 	   	
   	$this->form_validation('R');
   	if ($this->form_validation->run('R') && $this->upload->do_upload('pic'))
   	 {
   		$data=[
         'name'=>$this->input->post('name'),
         'email'=>$this->input->post('email'),
         'password'=>md5($this->input->post('confirm_Password')),
         'contact'=>$this->input->post('contact'),
         'gender'=>$this->input->post('gender'),
         'status'=>$this->input->post('status'),
         'profile'=>$_FILES['pic']['name'],
         'type'=>$this->input->post('type')
   		];
   		$this->mydb->add_user($data);
        $this->session->set_flashdata('success','New user add succesfully !');
   		redirect('mycnt/show_list','refresh');
   	 }
      	$this->load->view('user/add_user');
   } 
  // -------------------------------------------
        public function admin_panel(){
    	   // $this->loged_in('A');        	
           $this->load->view('user/admin_panel');
        }
   //-----------------------------------------------
        public function show_list(){

    	  $order_by= $this->input->get('order_by');
    	   $type= $this->input->get('type');        	        	

        $user['select']=$this->mydb->show_user_list($order_by,$type);	
        $this->load->view('user/show_list',$user);
        
        }
  //--------------------------------------------
       public function add_task(){
    	   // $this->loged_in('A');        	       	
       	$this->form_validation('T');
       	if ($this->form_validation->run('T'))
       	 {
       	 	$this->upload->do_upload('pic');
      
       		$task=[
            'title'      => $this->input->post('title'),
            'description'=> $this->input->post('desc'),
            'user'       => $this->input->post('user'),
            'task_status'=> $this->input->post('task_status'),
       	    'attechment'=>$_FILES['pic']['name']

       		];       	      	   
              $this->mydb->add_task($task);
  		$this->session->set_flashdata('success','New task add succesfully !');
              redirect('mycnt/task_manegment');
       	 } 
       	        	        	 
      $u['user'] = $this->mydb->show_user_list();
       	$this->load->view('user/add_task',$u);
       } 
  //----------------------------
  public function task_manegment(){
    	   // $this->loged_in('A');  
  $order_by =	$this->input->get('sort_fild');
  $type 	=   $this->input->get('sort_by');

    	   $this->load->helper('download');      	
     	$s = $this->input->get('search');
  	
  	$confi=[
            'base_url'   => base_url('index.php/mycnt/task_manegment'),
            'per_page'   => 8,
            'total_rows' => $this->mydb->show_task_num_row(),
			'reuse_query_string'=> true, 
			// 'use_page_numbers' => TRUE,
			'full_tag_open' => '<ul class="pagination">',
			'full_tag_close' => '</ul>',
			'prev_link' => '&laquo;',
			'prev_tag_open' => '<li>',
			'prev_tag_close' => '</li>',
			'next_tag_open' => '<li>',
			'next_tag_close' => '</li>',
			'cur_tag_open' => '<li class="active"><a href="#">',
			'cur_tag_close' => '</a></li>',
			'num_tag_open' => '<li>',
			'num_tag_close' => '</li>',
	         'next_link'    => '&raquo;'
  	   ];
  	 // $table = array('table_open' => '<table class="table table-bordered table-striped table-condensed">');  
    //  $header = array('S.no', 'Title','Description','Assign to','Status','Files','Time','Action');
    //    $this->table->set_template($table);
    //    $this->table->set_heading($header);

  	   $this->pagination->initialize($confi);
  	   $task['links'] =  $this->pagination->create_links() ;
       $task['user']=$this->mydb->task_m($confi['per_page'],$this->uri->segment(3),$s,$order_by,$type);
       $this->load->view('user/m_task',$task);
  } 
  //------------------------------------------ 
  public function edit_task()
  {
    	   // $this->loged_in('A');        	
  	$task_id = ($this->input->get('task_id')) ? $this->input->get('task_id') : $this->input->post('task_id') ;       
  	$this->form_validation('ET');
  	if ($this->form_validation->run('ET')) 
  	{

  		$data = [
               'title'      => $this->input->post('title'),
               'description'=> $this->input->post('desc'),
               'task_status'=> $this->input->post('task_status'),
  		];
  		if ($_FILES['pic']['name']!='')
     {
       	 	$this->upload->do_upload('pic');
     	    $data = ['attechment'=>$_FILES['pic']['name'] ];
     }
  		$this->mydb->update_task($data,$task_id);
  		$this->session->set_flashdata('success','Task edit succesfully !');
  		redirect ( 'mycnt/task_manegment');
  	}
       $task_data  = $this->mydb->edittask($task_id);
   	   $task['details'] = ($this->input->post()) ? (object) $this->input->post() : $task_data ;
  	   $this->load->view('user/edit_task',$task);
  }
  //----------------------------------------- 
  public function add_admin(){
    	   // $this->loged_in('A');        	  	
        $this->form_validation('A');
       if ($this->form_validation->run('A'))
         {
       	 	$this->upload->do_upload('pic');

           $data=[
            'name'   =>$this->input->post('name'),
            'email'   =>$this->input->post('email'),
            'password'=>$this->input->post('confi_pass'),
            'profile' => $_FILES['pic']['name'],
            'status'   =>$this->input->post('status'),
            'type'   =>$this->input->post('type'),
           ];
           $this->mydb->add_new_admin($data);
  		   $this->session->set_flashdata('success','New admin add succesfully !');
           redirect('mycnt/admin_panel');
           }
  	     $this->load->view('user/add_admin');
        }
   //--------------------------------- 
        public function delete($user_id){        	
        $this->mydb->delete($user_id);
  		$this->session->set_flashdata('success','delete user succesfully !');
        redirect('mycnt/show_list','refresh');
        }
//-------------------------------------------------------------------------------
       public function user_edit($user_id=''){
    	   // $this->loged_in('A');        	       	
       	$user_id = ($user_id) ? $user_id : $this->input->post('user_id');
       	$this->form_validation('E');
       	if ($this->form_validation->run('E'))
       	 {
           
       	 }        
        $user['data'] =  $this->mydb->show_edit($user_id);     	
       	$this->load->view('user/useredit',$user);

       } 
//-------------------------------------------------------------------
       public function user_task($t_id=''){
    	   // $this->loged_in('U');        	          
       	$user_id=$this->session->userdata('user_id')->user_id;
        $task_id = ($t_id)?$t_id:$this->input->post('task_id');
       	// $task_id=($this->input->get('task'))? $this->input->get('task') : $this->input->post('task_id');

       	$this->form_validation->set_rules('comment','Comment','required');
	    $this->form_validation->set_error_delimiters("<p class='text-danger'>","</p>");

       	if ($this->form_validation->run()) 
       	{
       		$config=['upload_path'        =>  'upload',
                 'allowed_types'      =>  'gif|jpg|png|JPEG|pdf|html'
                ];
             $this->load->library('upload',$config);
             $this->upload->do_upload('pic');
            $user = ($this->input->post('user_id'))? $this->input->post('user_id') : $this->input->post('user'); 
            $name =  $this->session->userdata('user_id')->name;
       		
       		$comment =
       		[
       		   'task_id'     =>     $this->input->post('task_id'),	
               'user_id'     =>     $user,
               'comment'     =>     $this->input->post('comment'),
               'c_attechment'=>     $this->input->post('pic'),
               'user_name'   =>     $name 
       		];
            $this->mydb->add_comment($comment);
            $this->session->set_flashdata('success','your comment was posted !')  ; 
              redirect("mycnt/user_task/$task_id");
       	  }

          $task['task'] = $this->mydb->utask($user_id,$task_id);
      	  $task['com']  = $this->mydb->user_comment($user_id,$task_id);
       	   $this->load->view('user/user_task',$task,'refresh');
       } 	

       public function back_assign($task_id)
       {
           $user_id=$this->session->userdata('user_id')->user_id;           
           $t = $this->mydb->utask($user_id,$task_id);
                                        
       		$data=[
            'task_status'=> 3,
       		]; 	
  	           	$this->mydb->update_task($data,$task_id);
  		       $this->session->set_flashdata('success','Task back to assign succesfully !');
               redirect('mycnt/user_task_list');
       	     	   
    
       }

       public function admin_check_task($t_id='')
       {
    	   // $this->loged_in('A');        	                 	
           $task_id = ($t_id) ? $t_id : $this->input->post('task_id');   
       	// $task_id=($this->input->get('task_id'))? $this->input->get('task_id') : $this->input->post('task_id');

       	$this->form_validation->set_rules('comment','Comment','required');
	    $this->form_validation->set_error_delimiters("<p class='text-danger'>","</p>");

       	if ($this->form_validation->run()) 
       	{
       		 $this->upload();

       		$comment =
       		[
       		   'task_id'     =>     $this->input->post('task_id'),	
               'user_id'     =>     $this->input->post('user_id'),
               'comment'     =>     $this->input->post('comment'),
               'c_attechment'=>     $this->input->post('pic'),
               'user_name'   =>     'Admin'
       		
       		];
           
            $this->mydb->add_comment($comment);
            $this->session->set_flashdata('success','your comment was posted !')  ; 
       	     redirect("mycnt/admin_check_task/$task_id");
       	  }
       	       $confi=[
            'base_url'   => base_url('index.php/mycnt/task_manegment'),
            'per_page'   => 8 ,
            'total_rows' => $this->mydb->a_check_num_row($t_id),
			'reuse_query_string'=> true, 
			// 'use_page_numbers' => TRUE,
			'full_tag_open' => '<ul class="pagination">',
			'full_tag_close' => '</ul>',
			'prev_link' => '&laquo;',
			'prev_tag_open' => '<li>',
			'prev_tag_close' => '</li>',
			'next_tag_open' => '<li>',
			'next_tag_close' => '</li>',
			'cur_tag_open' => '<li class="active"><a href="#">',
			'cur_tag_close' => '</a></li>',
			'num_tag_open' => '<li>',
			'num_tag_close' => '</li>',
	         'next_link'    => '&raquo;'
  	   ];
  	 // $table = array('table_open' => '<table class="table table-bordered table-striped table-condensed">');  
    //  $header = array('S.no', 'Title','Description','Assign to','Status','Files','Time','Action');
    //    $this->table->set_template($table);
    //    $this->table->set_heading($header);

  	            $this->pagination->initialize($confi);
  	            $task['links'] =  $this->pagination->create_links(); 
                $task['task'] = $this->mydb->a_check_task($task_id);
      	        $task['com'] = $this->mydb->a_check_comment($task_id,$confi['per_page'],$this->uri->segment(3));     	 	      	       	          
       	        $this->load->view('user/activity',$task);
       }
       public function del_comment($del_comment)
       {

           $task_id = $this->input->get('task');
       	   $this->mydb->delete_comment($del_comment); 
       	   if (isset($_SESSION['user_id']))
       	    {

           redirect("mycnt/user_task/{$task_id}");
       	         	
       	    }elseif(isset($_SESSION['admin_id'])){

           redirect("mycnt/admin_check_task/{$task_id}");
             }
       }
      public function user_status($user_id)
      {
      	  if ($this->input->get('status')==1)
		   {
		     $this->mydb->user_status($user_id,$this->input->get('status'));
		     redirect('mycnt/show_list','refresh');
		   }
		   elseif($this->input->get('status')==0)
		   {
		    $this->mydb->user_status($user_id,$this->input->get('status'));
		    redirect('mycnt/show_list','refresh');
		   }

      }     
      public function task_status($task_id){
        if ($this->input->get('task_status')==2)
		   {
		    $this->mydb->task_status($task_id,$this->input->get('task_status'));
		    redirect('mycnt/task_manegment','refresh');
		   }
		   elseif ($this->input->get('task_status')==1)
		   {
		    $this->mydb->task_status($task_id,$this->input->get('task_status'));
		    redirect('mycnt/task_manegment','refresh');
		   }
		    elseif ($this->input->get('task_status')==0)
		   {
		    $this->mydb->task_status($task_id,$this->input->get('task_status'));
		    redirect('mycnt/task_manegment','refresh');
		   }
         
      }
//--------------------------------------------------------
      // public function activity()
      // {    	
      // 	  $comment['com'] = $this->mydb->admin_comment();             	      	 
      // 	  $this->load->view('user/activity',$comment);
      // }  
  // -------------------------------------------
      // ------------------------
      // public function user_activity(){
      // 	    $user_id        =     $this->session->userdata('user_id')->user_id; 
      // 	 	$comment['com'] =     $this->mydb->user_comment($user_id);      	 	
      // 	 	$this->load->view('user/user_activity',$comment);
      // }
      public function user_task_list()
      {
    	   // $this->loged_in('U');        	                	
      	$search = $this->input->get('search');      	
       	$user_id=$this->session->userdata('user_id')->user_id;
        $task['task']=$this->mydb->user_task_l($user_id,$search);
        $this->session->set_userdata('back',$task['task']);
      	$this->load->view('user/my_task_list',$task);
      } 
      // public function reply(){
      // 	$user_id = ($this->input->get('user_id')) ? $this->input->get('user_id') : $this->input->post('user');
      //    $this->upload();
      //    $this->form_validation->set_rules('comment','Message','required');
	     // $this->form_validation->set_error_delimiters("<p class='text-danger'>","</p>");
      // 	if ($this->form_validation->run())
      // 	 {
      // 		$comment = [
      //         'user_id'      => $user_id,
      //         'comment'      => $this->input->post('comment'),
      //         'c_attechment' => $_FILES['pic']['name'],
      //         'admin_id'     => $_SESSION['admin_id']->user_id
      // 		];
      // 	  $this->mydb->add_comment($comment);
      // 	  $this->session->set_flashdata('success','Your Message was posted !');
      // 	  redirect('mycnt/activity');
      // 	 }
      // 	$this->load->view('user/replybox');
      // }
      public function upload(){

      	$config=['upload_path'        =>  'upload',
                 'allowed_types'      =>  'gif|jpg|png|JPEG|pdf|html'
                ];
             $this->load->library('upload',$config);
      }
      public function loged_in($type){

          if ($type=='U')
           {
           	$this->session->userdata('user_id');
            return	redirect('mycnt');
           }
           if($type=='A')
           {
           	   $this->session->userdata('admin_id');
                return  redirect('mycnt');           	
           }
      }
        public function logout()
        {
        	$this->session->sess_destroy();
        	redirect('mycnt');
        }                
}
?>
