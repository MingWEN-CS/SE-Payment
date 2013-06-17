 // 本类由系统自动生成，仅供测试用途
 class AdminAction extends Action {
     
    Public function login() {
        if(IS_AJAX) {
            $adminname = $this->_post('adminname');
            $condition['name'] = $adminname;
            $password = $this->_post('password');
            $se_admin = D("Admin");
            $admin = $se_admin->where($condition)->find();
            if (!$admin){
                $this->ajaxReturn('', 'Admin Does Not Exsit', 0);
            }
            else if ($password != $admin[password]){
                $this->ajaxReturn('', 'Wrong Password', 0);
            }
            else {
                $this->ajaxReturn('', 'Login Successfully', 1);
            }
        }


   Public function admin() {
       $this->display();
   }
 }
 
