<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Static extends Controller {

    public function action_index()
    {
        $file = $this->request->param('catchall');

        if ( '' == $file )
        {
            $this->response->body(View::factory('layout'));
        }
        elseif ( 'assets/js/app.js' === $file )
        {
            $app_js = array();
            $app_js[] = file_get_contents(Kohana::find_file('views', 'assets/js/header.partial_js', false));
            $app_js[] = file_get_contents(Kohana::find_file('views', 'assets/js/config.js', false));
            $app_js[] = file_get_contents(Kohana::find_file('views', 'assets/js/data.js', false));
            $app_js[] = file_get_contents(Kohana::find_file('views', 'assets/js/authCtrl.js', false));
            $app_js[] = file_get_contents(Kohana::find_file('views', 'assets/js/directives.js', false));
            $app_js[] = file_get_contents(Kohana::find_file('views', 'assets/js/angular-jwt.js', false));
            $app_js[] = file_get_contents(Kohana::find_file('views', 'assets/js/footer.partial_js', false));
            
            $this->response->body(implode('', $app_js));
            $this->response->send_file(true, 'app.js', array(
                'inline' => true,
                'mime_type' => 'text/javascript',
            ));
        }
        else
        {
            if ( false === ($path = Kohana::find_file('views', $file, false)) )
            {
                throw HTTP_Exception::factory(404);
            }

            $this->response->send_file($path, null, array(
                'inline' => true,
                'mime_type' => File::mime_by_ext(pathinfo($path, PATHINFO_EXTENSION)),
            ));
        }
    }
    
}