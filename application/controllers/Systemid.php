<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author Lukasz Sosna
 * @copyright 2019
 * @e-mail tree@interia.pl
 * @e-mail support@phpbluedragon.eu
 * @www http://phpbluedragon.eu
 */
 
class Systemid extends CI_Controller
{
    private $IsWykopApiIn = false;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('System_model');
        $this->load->library('form_validation');
        //$this->output->enable_profiler(true);
        
        $this->lang->load('system', $this->config->item('language'));
    }
    
    /*
    TODO
    
    */
    
    public function index($Action='',$SubAction="")
    {        
        if($_SESSION['user_id'] == "")
        {
            if($this->input->post('formlogin') == 'yes')
    		{
    			$this->form_validation->set_rules('user_email', ''.$this->lang->line('a0860').'', 'required|valid_email');
    			$this->form_validation->set_rules('user_password', ''.$this->lang->line('a0861').'', 'required');
    
    			if($this->form_validation->run() != FALSE)
    			{
    				$TableUser = $this->System_model->CheckUser();

    				if($TableUser['IsAuth'] == 'no')
                    {
                        $SystemLang['bad_data'] = true;
                    }
                    
                    if($TableUser['IsAuth'] == 'yes')
                    {
                        $_SESSION['user_id'] = $TableUser['UserId'];
                        redirect();
                    }
                }
            }
            
            $SystemLang['Title'] = ''.$this->lang->line('a0862').'';
            $SystemLang['Content'] = ''.$this->lang->line('a0863').'';
            
            $this->load->view('head',$SystemLang);
            
            $this->load->view('login', $SystemLang);
            
            $this->load->view('foot');
        }
        else
        {
            $SystemLang['Title'] = ''.$this->lang->line('a0864').'';
            $SystemLang['Content'] = ''.$this->lang->line('a0865').'';
            
            $this->load->view('head',$SystemLang);

            $this->load->view('wykop/show', $SystemLang);
            
            $this->load->view('foot');
        }
    }
    
    public function microblogplus()
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        if($this->input->post('formlogin') == 'yes')
        {        
            $ReturnSession = $this->selectsession();
            //$ReturnSession['ValidToken'];
            //$ReturnSession['ValidKey'];
            //$ReturnSession['ValidSecret'];
            
            if($this->IsWykopApiIn == false)
            {
                include('library/wykopapi.php');
                $this->IsWykopApiIn = true;
            }
            $WykopApi = new libs_Wapi($ReturnSession['ValidKey'], $ReturnSession['ValidSecret']);
            
            $ResultArray = null;

            $SystemLang['Comunicat'] = 'ok';
            $UserArray = $WykopResult;
            
            $result = $WykopApi->doRequest('stream/hot/userkey/'.$ReturnSession['ValidToken'].'/page/1/period/12');

            if ($WykopApi->isValid())
            {
                $ArrayId = 0;
                
                foreach ($result as $r)
                {
                    $ResultArray[$ArrayId] = $r;
                    $ArrayId++;
                }
            } 
            else 
            {
                $ResultError = $WykopApi->getError();
            }
        
        
            //echo '<pre>';
            //print_r($ResultError);
            //print_r($UserArray);
            //print_r($ResultArray);
            //echo '</pre>';
            
            $s = 0;
            $ViewData = '
            <div class="row">
            <div class="col-md-2"><strong>'.$this->lang->line('a1204').'</strong></div>
            <div class="col-md-7"><strong>'.$this->lang->line('a1205').'</strong></div>
            <div class="col-md-2"><strong>'.$this->lang->line('a1206').'</strong></div>
            <div class="col-md-1"><strong>'.$this->lang->line('a1207').'</strong></div>
            </div>';
            
            //$dTest = 0;
            
            foreach($ResultArray as $RowItem)
            {
                $RowItem['UserWasWoted'] = false;
                
                //echo '<pre>';
                //print_r($RowItem);
                //echo '</pre>';
                
                $ItemId = $RowItem['id'];
                
                    if($RowItem['user_vote'] == null)
                    {
                        //echo 'null ';
                        $result = $WykopApi->doRequest('entries/vote/entry/'.$ItemId.'/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey']);
                        //Entries
                        if (!$WykopApi->isValid())
                        {
                            $ResultError = $WykopApi->getError();
                            //echo '<pre>';
                            //print_r($ResultError);
                            //echo '</pre>';
                        }
                    }
                    else
                    {
                        $RowItem['UserWasWoted'] = true;
                    }
                    
                    /*
                    $dTest++;
                    if($dTest > 0)
                    {
                        break;
                    }
                    */

                
                $TableResult[$s]['id'] = $RowItem['id'];
                $TableResult[$s]['body'] = substr($RowItem['body'],0,255);
                $TableResult[$s]['vote_count'] = $RowItem['vote_count'];
                $TableResult[$s]['UserWasWoted'] = $RowItem['UserWasWoted'];
                
                if($RowItem['UserWasWoted'])
                {
                    $Voted = ''.$this->lang->line('a1208').'';
                }
                else
                {
                    $Voted = ''.$this->lang->line('a1209').'';
                }
                
                //'.$RowItem['user_vote'].'
                
                $ViewData .= '
                <div class="row">
                <div class="col-md-2">'.$RowItem['id'].'</div>
                <div class="col-md-7">'.substr($RowItem['body'],0,255).'</div>
                <div class="col-md-2">'.$Voted.'</div>
                <div class="col-md-1">'.$RowItem['vote_count'].'</div>
                </div>';
                
                $s++;
            }
            
            $ViewData .= '<br /><br />';
            
            $SystemLang['ViewData'] = $ViewData;
            
            unset($WykopApi);
   
            if($ResultArray == null)
            {
                $SystemLang['ConnectProblem'] = true;
            }
            else
            {
                $SystemLang['ResultArray'] = $ResultArray;
                $SystemLang['UserArray'] = $UserArray;
            }
        }
        
        $SystemLang['Title'] = ''.$this->lang->line('a1210').'';
        $SystemLang['UrlFraze'] = 'topplusminus';
        
        $SystemLang['Content'] = ''.$this->lang->line('a1211').'';
        
        $this->load->view('head',$SystemLang);
        
        $this->load->view('wykop/microblogplus', $SystemLang);
            
        $this->load->view('foot');
    }
    
    public function wykopplusminus($What)
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        if($this->input->post('formlogin') == 'yes')
        {        
            $ReturnSession = $this->selectsession();
            //$ReturnSession['ValidToken'];
            //$ReturnSession['ValidKey'];
            //$ReturnSession['ValidSecret'];
            
            if($this->IsWykopApiIn == false)
            {
                include('library/wykopapi.php');
                $this->IsWykopApiIn = true;
            }
            $WykopApi = new libs_Wapi($ReturnSession['ValidKey'], $ReturnSession['ValidSecret']);
            
            $ResultArray = null;

            $SystemLang['Comunicat'] = 'ok';
            $UserArray = $WykopResult;
            
            if($What == 'top')
            {
                $result = $WykopApi->doRequest('links/promoted/userkey/'.$ReturnSession['ValidToken']);
            }
            elseif($What == 'dig')
            {
                $result = $WykopApi->doRequest('links/upcoming/userkey/'.$ReturnSession['ValidToken']);
            }

            if ($WykopApi->isValid())
            {
                $ArrayId = 0;
                
                foreach ($result as $r)
                {
                    $ResultArray[$ArrayId] = $r;
                    $ArrayId++;
                }
            } 
            else 
            {
                $ResultError = $WykopApi->getError();
            }

            $s = 0;
            $ViewData = '
            <div class="row">
            <div class="col-md-2"><strong>'.$this->lang->line('a1212').'</strong></div>
            <div class="col-md-7"><strong>'.$this->lang->line('a1213').'</strong></div>
            <div class="col-md-2"><strong>'.$this->lang->line('a1214').'</strong></div>
            <div class="col-md-1"><strong>'.$this->lang->line('a1215').'</strong></div>
            </div>';
            
            foreach($ResultArray as $RowItem)
            {
                $RowItem['UserWasWoted'] = null;
                
                $ItemId = $RowItem['id'];
                
                if($What == 'top')
                {
                    if($RowItem['user_vote'] == "")
                    {
                        $result = $WykopApi->doRequest('link/dig/'.$ItemId.'/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey']);
                    }
                    else
                    {
                        $RowItem['UserWasWoted'] = true;
                    }
                }
                elseif($What == 'dig')
                {
                    if($RowItem['user_vote'] == "")
                    {
                        $result = $WykopApi->doRequest('link/bury/'.$ItemId.'/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey']);
                    }
                    else
                    {
                        $RowItem['UserWasWoted'] = true;
                    }
                }
                
                $TableResult[$s]['id'] = $RowItem['id'];
                $TableResult[$s]['title'] = $RowItem['title'];
                $TableResult[$s]['vote_count'] = $RowItem['vote_count'];
                $TableResult[$s]['UserWasWoted'] = $RowItem['UserWasWoted'];
                
                if($RowItem['UserWasWoted'])
                {
                    $Voted = ''.$this->lang->line('a1216').'';
                }
                else
                {
                    $Voted = ''.$this->lang->line('a1217').'';
                }
                
                $ViewData .= '
                <div class="row">
                <div class="col-md-2">'.$RowItem['id'].'</div>
                <div class="col-md-7">'.$RowItem['title'].'</div>
                <div class="col-md-2">'.$Voted.'</div>
                <div class="col-md-1">'.$RowItem['vote_count'].'</div>
                </div>';
                
                $s++;
            }
            
            $ViewData .= '<br /><br />';
            
            $SystemLang['ViewData'] = $ViewData;

            unset($WykopApi);

            if($ResultArray == null)
            {
                $SystemLang['ConnectProblem'] = true;
            }
            else
            {
                $SystemLang['ResultArray'] = $ResultArray;
                $SystemLang['UserArray'] = $UserArray;
            }
        }
        
        if($What == 'top')
        {
            $SystemLang['Title'] = ''.$this->lang->line('a1218').'';
            $SystemLang['UrlFraze'] = 'topplusminus';
        }
        else
        {
            $SystemLang['Title'] = ''.$this->lang->line('a1219').'';
            $SystemLang['UrlFraze'] = 'digplusminus';
        }
        
        $SystemLang['Content'] = ''.$this->lang->line('a1220').'';
        
        $this->load->view('head',$SystemLang);
        
        $this->load->view('wykop/wykopplusminus', $SystemLang);
            
        $this->load->view('foot');
    }
    
    public function wykopplusminuscomm($What)
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        if($this->input->post('formlogin') == 'yes')
        {        
            $ReturnSession = $this->selectsession();
            //$ReturnSession['ValidToken'];
            //$ReturnSession['ValidKey'];
            //$ReturnSession['ValidSecret'];
            
            if($this->IsWykopApiIn == false)
            {
                include('library/wykopapi.php');
                $this->IsWykopApiIn = true;
            }
            $WykopApi = new libs_Wapi($ReturnSession['ValidKey'], $ReturnSession['ValidSecret']);
            
            $ResultArray = null;
                
            $SystemLang['Comunicat'] = 'ok';
            $UserArray = $WykopResult;
            
            if($What == 'top')
            {
                $result = $WykopApi->doRequest('links/promoted/userkey/'.$ReturnSession['ValidToken']);
            }
            elseif($What == 'dig')
            {
                $result = $WykopApi->doRequest('links/upcoming/userkey/'.$ReturnSession['ValidToken']);
            }

            if ($WykopApi->isValid())
            {
                $ArrayId = 0;
                
                foreach ($result as $r)
                {
                    $ResultArray[$ArrayId] = $r;
                    $ArrayId++;
                }
            } 
            else 
            {
                $ResultError = $WykopApi->getError();
            }
                        
            $PozitionNumber = $this->input->post('add_what_poz');
            
            if(!is_numeric($PozitionNumber))
            {
                $PozitionNumber = 1;
            }
            
            if($PozitionNumber == 0)
            {
                $PozitionNumber = 1;
            }
            
            $ViewData = '
            <div class="row">
            <div class="col-md-2"><strong>'.$this->lang->line('a1221').'</strong></div>
            <div class="col-md-2"><strong>'.$this->lang->line('a1222').'</strong></div>
            <div class="col-md-7"><strong>'.$this->lang->line('a1223').'</strong></div>
            <div class="col-md-1"><strong>'.$this->lang->line('a1224').'</strong></div>
            </div>';
            
            foreach($ResultArray as $RowItem)
            {
                $ItemId = $RowItem['id'];
                
                $result2 = $WykopApi->doRequest('link/comments/'.$ItemId.'/userkey/'.$ReturnSession['ValidToken']);
                
                if ($WykopApi->isValid())
                {
                    $ArrayId2 = 0;
                    
                    $CommPoz = 1;
                    
                    foreach ($result2 as $r2)
                    {
                        $ResultArray[$ArrayId2] = $r2;
                        
                        //$result = $WykopApi->doRequest('comments/plus/'.$ItemId.'/userkey/'.$WykopResult['userkey']);
                        
                        $MyVoteIs = '';
                        
                        if($CommPoz == $PozitionNumber)
                        {
                            if($this->input->post('add_what') == 'plus')
                            {
                                $result = $WykopApi->doRequest('comments/plus/'.$ItemId.'/'.$r2['id'].'/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey']);
                                $MyVoteIs = '+';
                            }
                            elseif($this->input->post('add_what') == 'minus')
                            {
                                $result = $WykopApi->doRequest('comments/minus/'.$ItemId.'/'.$r2['id'].'/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey']);
                                $MyVoteIs = '-';
                            }
                            else
                            {
                                if($r2['vote_count'] >= 0)
                                {
                                    $result = $WykopApi->doRequest('comments/plus/'.$ItemId.'/'.$r2['id'].'/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey']);
                                    $MyVoteIs = '+';
                                }
                                else
                                {
                                    $result = $WykopApi->doRequest('comments/minus/'.$ItemId.'/'.$r2['id'].'/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey']);
                                    $MyVoteIs = '-';
                                }
                            }
                            
                            //$ViewData .= '<br />'.$ItemId.' - '.$r2['id'].' - '.$r2['readed'].' - '.$r2['body'].' - '.$r2['vote_count'].'<br />';
                            
                            $ViewData .= '
                            <div class="row">
                            <div class="col-md-2">'.$ItemId.'</div>
                            <div class="col-md-2">'.$r2['id'].'</div>
                            <div class="col-md-7">'.$r2['body'].'</div>
                            <div class="col-md-1">'.$r2['vote_count'].'<br /> <span class="alert alert-success">'.$MyVoteIs.'</span></div>
                            </div>';
                        }
                        
                        $ArrayId2++;
                        
                        $CommPoz++;
                    }
                } 
                else 
                {
                    $ResultError = $WykopApi->getError();
                } 
            }
            
            $ViewData .= '<br /><br />';
            
            $s = 0;
            
            $SystemLang['ViewData'] = $ViewData;

            unset($WykopApi);
 
            if($ResultArray == null)
            {
                $SystemLang['ConnectProblem'] = true;
            }
            else
            {
                $SystemLang['ResultArray'] = $ResultArray;
                $SystemLang['UserArray'] = $UserArray;
            }
        }
        
        if($What == 'top')
        {
            $SystemLang['Title'] = ''.$this->lang->line('a1225').'';
            $SystemLang['UrlFraze'] = 'topcommplusminus';
        }
        else
        {
            $SystemLang['Title'] = ''.$this->lang->line('a1226').'';
            $SystemLang['UrlFraze'] = 'digcommplusminus';
        }
        
        $SystemLang['Content'] = ''.$this->lang->line('a1227').'';
        
        $this->load->view('head',$SystemLang);
        
        $this->load->view('wykop/wykopplusminuscomm', $SystemLang);
            
        $this->load->view('foot');
    }
    
    public function searchinsertsinwykop($ItemId)
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        $ReturnSession = $this->selectsession();
        //$ReturnSession['ValidToken'];
        //$ReturnSession['ValidKey'];
        //$ReturnSession['ValidSecret'];
        
        if($this->IsWykopApiIn == false)
        {
            include('library/wykopapi.php');
            $this->IsWykopApiIn = true;
        }
        $WykopApi = new libs_Wapi($ReturnSession['ValidKey'], $ReturnSession['ValidSecret']);
            
        $ResultArray = null;

        $SystemLang['Comunicat'] = 'ok';
        $UserArray = $WykopResult;
        
        $result = $WykopApi->doRequest('link/index/'.$ItemId.'/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey']);

        
        if ($WykopApi->isValid())
        {             
            $ArrayId = 0;
            
            foreach ($result as $r)
            {
                $ResultArray[$ArrayId] = $r;
                $ArrayId++;
            }
        } 
        else 
        {
            $ResultError = $WykopApi->getError();
        }
                
        unset($WykopApi);
            
        if($ResultArray == null)
        {
            //błąd połączenia
            $ValueToReturn = 'empty';    
        }
        else
        {
            //zrobione
            $ValueToReturn = 'result';
            /*foreach($ResultArray as $Key=>$Value)
            {
                echo $Key.'|||||'.$Value."\n";
            }*/
        }
        
        if($ValueToReturn == "")
        {
            $ValueToReturn = 'empty';
        }
        
        return $ValueToReturn;
    }

    public function searchinsertsinwykop2($ItemId)
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        $ReturnSession = $this->selectsession();
        //$ReturnSession['ValidToken'];
        //$ReturnSession['ValidKey'];
        //$ReturnSession['ValidSecret'];
        
        //include('library/wykopapi.php');
        if($this->IsWykopApiIn == false)
        {
            include('library/wykopapi.php');
            $this->IsWykopApiIn = true;
        }
        
        $WykopApi = new libs_Wapi($ReturnSession['ValidKey'], $ReturnSession['ValidSecret']);
        
        $ResultArray = null;

        $SystemLang['Comunicat'] = 'ok';
        $UserArray = $WykopResult;
        
        $result = $WykopApi->doRequest('link/index/'.$ItemId.'/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey']);

        
        if ($WykopApi->isValid())
        {             
            $ArrayId = 0;
            
            /*foreach ($result as $r)
            {
                $ResultArray[$ArrayId] = $r;
                $ArrayId++;
            }*/
            
            $ResultArray[] = $result;
        } 
        else 
        {
            $ResultError = $WykopApi->getError();
        }
                
        unset($WykopApi);
            
        if($ResultArray == null)
        {
            //błąd połączenia
            $ValueToReturn = 'empty';    
        }
        else
        {
            //zrobione
            $ValueToReturn = $ResultArray;
            /*foreach($ResultArray as $Key=>$Value)
            {
                echo $Key.'|||||'.$Value."\n";
            }*/
        }   
        
        if($ValueToReturn == "")
        {
            $ValueToReturn = 'empty';
        }
        
        return $ValueToReturn;
    }
        
    public function addcomment()
    {
        //error_reporting(E_ALL);
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        if($this->input->post('formlogin') == 'yes')
        {
            $this->form_validation->set_rules('key_id', ''.$this->lang->line('a1228').'', 'required|numeric');
            
            if($this->form_validation->run() != FALSE)
            {
                $ResultFromWykop = $this->searchinsertsinwykop($this->input->post('key_id'));

                if($ResultFromWykop == 'result')
                {
                    //echo 'addcomment2/'.$this->input->post('key_id');
                    redirect(base_url('addcomment2/'.$this->input->post('key_id')));
                    exit();
                }
                else
                {
                    $SystemLang['NoThatEntry'] = true;
                }
                //$this->System_model->AccessAddNew();
            
                //$SystemLang['IsAdded'] = true;
            }
        }
        
        $SystemLang['Title'] = ''.$this->lang->line('a1229').'';
        $SystemLang['Content'] = ''.$this->lang->line('a1230').'';
        
        $this->load->view('head',$SystemLang);
        
        $this->load->view('wykop/addcomment', $SystemLang);
            
        $this->load->view('foot');
    }
    
    public function doNothing() {}
    
    public function addnewcommentwykop($Entry,$PostBody,$AttachFile='')
    {
        /*echo '<pre>';
        print_r($Entry);
        print_r($PostBody);
        print_r($AttachFile);        
        echo '</pre>';*/
        
        if($_SESSION['user_id'] == ""){redirect();exit();}
                
        $ReturnSession = $this->selectsession();
        //$ReturnSession['ValidToken'];
        //$ReturnSession['ValidKey'];
        //$ReturnSession['ValidSecret'];
        
        if($libs_Wapi == null)
        {
            include('library/wykopapi.php');
        }
        
        $WykopApi = new libs_Wapi($ReturnSession['ValidKey'], $ReturnSession['ValidSecret']);
        
        $ResultArray = null;
        
        $SystemLang['Comunicat'] = 'ok';
        $UserArray = $WykopResult;
        
        $result = $WykopApi->doRequest('comments/add/'.$Entry.'/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey'],
        $PostBody,
        $AttachFile);
        
        if ($WykopApi->isValid())
        {             
            $ResultArray = $result;
        } 
        else 
        {
            $ResultError = $WykopApi->getError();
        }
            
        unset($WykopApi);
        
        if($ResultArray == null)
        { 
            $ValueToReturn = $ResultError;
        }
        else
        {
            $ValueToReturn = $ResultArray;
        }
        
        if($ValueToReturn == "")
        {
            $ValueToReturn = 'error2';
        }
        
        return $ValueToReturn;
    }
    
    public function addnewentrywykop($PostBody,$AttachFile='')
    {
        /*echo '<pre>';
        print_r($Entry);
        print_r($PostBody);
        print_r($AttachFile);        
        echo '</pre>';*/
        
        if($_SESSION['user_id'] == ""){redirect();exit();}
                
        $ReturnSession = $this->selectsession();
        //$ReturnSession['ValidToken'];
        //$ReturnSession['ValidKey'];
        //$ReturnSession['ValidSecret'];
        
        if($this->IsWykopApiIn == false)
        {
            include('library/wykopapi.php');
            $this->IsWykopApiIn = true;
        }
        
        $WykopApi = new libs_Wapi($ReturnSession['ValidKey'], $ReturnSession['ValidSecret']);

        $ResultArray = null;
            
        $SystemLang['Comunicat'] = 'ok';
        $UserArray = $WykopResult;
        
        $result = $WykopApi->doRequest('entries/add/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey'],
        $PostBody,
        $AttachFile);
        
        if ($WykopApi->isValid())
        {             
            $ResultArray = $result;
        } 
        else 
        {
            $ResultError = $WykopApi->getError();
        }
               
        unset($WykopApi);
                
        if($ResultArray == null)
        { 
            $ValueToReturn = $ResultError;
        }
        else
        {
            $ValueToReturn = $ResultArray;
        } 

        if($ValueToReturn == "")
        {
            $ValueToReturn = 'error2';
        }
        
        return $ValueToReturn;
    }
    
    public function checkiscron1($Val)
    {
        if($this->input->post('period_what') == 'one')
        {
            return true;
        }
        else
        {
            if($this->input->post('period_date_1') == '')
            {
                $this->form_validation->set_message('checkiscron1', ''.$this->lang->line('a1231').'');
                return false;
            }
            else
            {
                if(DateTime::createFromFormat('Y-m-d H:i:s', $this->input->post('period_date_1')) === false)
                {
                    $this->form_validation->set_message('checkiscron1', ''.$this->lang->line('a1232').'');
                    return false;
                }
                else
                {
                    return true;
                }
            }
        }
    }
    
    public function checkiscron2($Val)
    {
        if($this->input->post('period_what') == 'one')
        {
            return true;
        }
        else
        {
            if($this->input->post('period_date_2') == '')
            {
                $this->form_validation->set_message('checkiscron2', ''.$this->lang->line('a1233').'');
                return false;
            }
            else
            {
                if(DateTime::createFromFormat('Y-m-d H:i:s', $this->input->post('period_date_2')) === false)
                {
                    $this->form_validation->set_message('checkiscron2', ''.$this->lang->line('a1234').'');
                    return false;
                }
                else
                {
                    return true;
                }
            }
        }
    }
    
    public function checkdateafterfirst($Val)
    {
        if($this->input->post('period_what') == 'one')
        {
            return true;
        }
        else
        {
            if(strtotime($this->input->post('period_date_2')) > strtotime($this->input->post('period_date_1')))
            {
                return true;
            }
            else
            {
                $this->form_validation->set_message('checkdateafterfirst', ''.$this->lang->line('a1235').'');
                return false;
            }
        }
    }
    
    public function addentry()
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        if($this->input->post('formlogin') == 'yes')
        {
            $this->form_validation->set_rules('comment_body', ''.$this->lang->line('a1236').'', 'required');
            $this->form_validation->set_rules('comment_file', ''.$this->lang->line('a1237').'', 'callback_graphicfile|callback_graphicextension');
            
            //$this->form_validation->set_rules('period_what', 'Dodaj', 'callback_checkiscron');
            $this->form_validation->set_rules('period_date_1', ''.$this->lang->line('a1238').'', 'callback_checkiscron1');
            $this->form_validation->set_rules('period_date_2', ''.$this->lang->line('a1239').'', 'callback_checkiscron2|callback_checkdateafterfirst');
            
            if($this->form_validation->run() != FALSE)
            {
                $config['upload_path'] = './uploads/';
        		$config['allowed_types'] = 'jpg|png|gif';
        		$config['max_size']	= '10000';
                $config['remove_spaces'] = true;
                                
        		$this->load->library('upload', $config);
        
                if($_FILES['comment_file2']['name'] != "")
                {
            		if(!$this->upload->do_upload('comment_file2'))
            		{
            			$SystemLang['UploadError'] = array('error' => $this->upload->display_errors());
            		}
            		else
            		{
                        $SystemLang['UploadData'] = $this->upload->data();
                    }
                }
                
                //array('embed' => "@plik.jpg;type=image/jpeg")
                //array('body' => 'komentarz dodany przez API'
                
                $PostBody['body'] = $this->input->post('comment_body');
                if($this->input->post('comment_file') != "")
                {
                    $PostBody['embed'] = $this->input->post('comment_file');
                }
                
                if($SystemLang['UploadData']['full_path'] != "")
                {
                    if($SystemLang['UploadData']['image_type'] == 'gif')
                    {
                        $ImageTyle = 'image/gif';
                    }
                    elseif($SystemLang['UploadData']['image_type'] == 'png')
                    {
                        $ImageTyle = 'image/png';
                    }
                    else
                    {
                        $ImageTyle = 'image/jpeg';
                    }
                    
                    //$AttachFile['embed'] = "@".base_url($SystemLang['UploadData']['full_path']).";type=".$ImageTyle."";
                    $AttachFile['embed'] = base_url($SystemLang['UploadData']['full_path']);
                }
                
                //if($this->input->post('period_what') == 'one')
                //{
                    $Result = $this->addnewentrywykop($PostBody,$AttachFile);
                    
                    //if($Result)
                    //echo '<pre>';
                    //echo print_r($Result);
                    //echo '</pre>';
                    
                    if(is_array($Result))
                    {
                        $SystemLang['IsOk'] = 'ok';
                        $SystemLang['CommentId'] = $Result['id'];
                        
                        $SystemLang['Vcomment_body'] = '';
                        $SystemLang['Vcomment_file'] = '';
                        
                        $SystemLang['Vperiod_what'] = '';
                        $SystemLang['Vperiod_date_1'] = '';
                        $SystemLang['Vperiod_date_2'] = '';
                        $SystemLang['Vperiod_period'] = '';
                    }
                    else
                    {
                        $SystemLang['IsOk'] = 'no';
                        $SystemLang['CommentId'] = $Result;
                        
                        $SystemLang['Vcomment_body'] = $this->input->post('comment_body');
                        $SystemLang['Vcomment_file'] = $this->input->post('comment_file');
                        
                        $SystemLang['Vperiod_what'] = $this->input->post('period_what');
                        $SystemLang['Vperiod_date_1'] = $this->input->post('period_date_1');
                        $SystemLang['Vperiod_date_2'] = $this->input->post('period_date_2');
                        $SystemLang['Vperiod_period'] = $this->input->post('period_period');
                    }
                /*
                }
                else
                {
                    //$Result = $this->addnewentrywykopcron($PostBody,$AttachFile);
                    
                    $this->System_model->AddNewCronJobAddEntry($PostBody['body'],$PostBody['embed'],$AttachFile['embed']);
                    
                    $SystemLang['IsOk2'] = 'ok';
                    //$SystemLang['CommentId'] = $Result['id'];
                    
                    $SystemLang['Vcomment_body'] = '';
                    $SystemLang['Vcomment_file'] = '';
                    
                    $SystemLang['Vperiod_what'] = '';
                    $SystemLang['Vperiod_date_1'] = '';
                    $SystemLang['Vperiod_date_2'] = '';
                    $SystemLang['Vperiod_period'] = '';
                }
                */
            }
            else
            {
                $SystemLang['Vcomment_body'] = $this->input->post('comment_body');
                $SystemLang['Vcomment_file'] = $this->input->post('comment_file');
                
                $SystemLang['Vperiod_what'] = $this->input->post('period_what');
                $SystemLang['Vperiod_date_1'] = $this->input->post('period_date_1');
                $SystemLang['Vperiod_date_2'] = $this->input->post('period_date_2');
                $SystemLang['Vperiod_period'] = $this->input->post('period_period');
            }
        }
        
        $SystemLang['EntryId'] = $EntryId;
        
        $SystemLang['Title'] = ''.$this->lang->line('a1240').'';
        $SystemLang['Content'] = ''.$this->lang->line('a1241').'';
        
        $this->load->view('head',$SystemLang);
        
        $this->load->view('wykop/addentry', $SystemLang);
            
        $this->load->view('foot');
    }
    
    //private function addnewentrywykopcron($PostBody,$AttachFile='')
    //{
    //    $this->System_model->AddNewCronJobAddEntry($PostBody['body'],$PostBody['embed'],$AttachFile['embed']);
    //}
    
    public function checkhour1($Val)
    {
        $Val = '2017-06-18 '.$Val;
        
        if(strtotime($Val) === false)
        {
            $this->form_validation->set_message('checkhour1', ''.$this->lang->line('a1242').'');
            return false;
        }
        else
        {
            return true;
        }
    }
    
    public function checkhour2($Val)
    {
        $Val = '2017-06-18 '.$Val;
        
        if(strtotime($Val) === false)
        {
            $this->form_validation->set_message('checkhour2', ''.$this->lang->line('a1243').'');
            return false;
        }
        else
        {
            return true;
        }
    }
    
    public function ismoretime($Val)
    {
        $Val1 = '2017-06-18 '.$this->input->post('cron_start');
        $Val2 = '2017-06-18 '.$this->input->post('cron_stop');
        
        if(strtotime($Val1) < strtotime($Val2))
        {
            return true;
        }
        else
        {
            $this->form_validation->set_message('ismoretime', ''.$this->lang->line('a1244').'');
            return false;
        }
    }
    
    public function cronsetup()
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        $SystemLang['Title'] = ''.$this->lang->line('a1245').'';
        $SystemLang['Content'] = ''.$this->lang->line('a1246').'';
        
        $this->load->view('head',$SystemLang);

        if($this->input->post('formlogin') == 'yes')
        {
            $this->form_validation->set_rules('cron_start', ''.$this->lang->line('a1247').'', 'required|callback_checkhour1');
            $this->form_validation->set_rules('cron_stop', ''.$this->lang->line('a1248').'', 'required|callback_checkhour2|callback_ismoretime');
            
            if($this->form_validation->run() != FALSE)
            {
                $this->System_model->CronSetSettings();
                
                $SystemLang['IsAdded'] = true;
            }
            
            $SystemLang['Vcron_top'] = $this->input->post('cron_top');
            $SystemLang['Vcron_dig'] = $this->input->post('cron_dig');
            $SystemLang['Vcron_microblog'] = $this->input->post('cron_microblog');
            $SystemLang['Vcron_comm_top'] = $this->input->post('cron_comm_top');
            $SystemLang['Vcron_comm_howmany_top'] = $this->input->post('cron_comm_howmany_top');
            $SystemLang['Vcron_comm_dig'] = $this->input->post('cron_comm_dig');
            $SystemLang['Vcron_comm_howmany_dig'] = $this->input->post('cron_comm_howmany_dig');
            $SystemLang['Vcron_start'] = $this->input->post('cron_start');
            $SystemLang['Vcron_stop'] = $this->input->post('cron_stop');
        }
        else
        {
            $ResultDB = $this->System_model->CronSelectSetup();
        
            foreach($ResultDB->result() as $row)
            {
                $SystemLang['Vcron_top'] = $row->cron_top;
                $SystemLang['Vcron_dig'] = $row->cron_dig;
                $SystemLang['Vcron_microblog'] = $row->cron_microblog;
                $SystemLang['Vcron_comm_top'] = $row->cron_comm_top;
                $SystemLang['Vcron_comm_howmany_top'] = $row->cron_comm_howmany_top;
                $SystemLang['Vcron_comm_dig'] = $row->cron_comm_dig;
                $SystemLang['Vcron_comm_howmany_dig'] = $row->cron_comm_howmany_dig;
                $SystemLang['Vcron_start'] = $row->cron_start;
                $SystemLang['Vcron_stop'] = $row->cron_stop;
            }
        }
        
        $this->load->view('wykop/cronsetup', $SystemLang);
            
        $this->load->view('foot');
    }
    
    public function addcomment2($EntryId)
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        if($this->input->post('formlogin') == 'yes')
        {
            $this->form_validation->set_rules('comment_body', ''.$this->lang->line('a1249').'', 'required');
            $this->form_validation->set_rules('comment_file', ''.$this->lang->line('a1250').'', 'callback_graphicfile|callback_graphicextension');
            
            if($this->form_validation->run() != FALSE)
            {
                $config['upload_path'] = './uploads/';
        		$config['allowed_types'] = 'jpg|png|gif';
        		$config['max_size']	= '10000';
                $config['remove_spaces'] = true;
                                
        		$this->load->library('upload', $config);
        
                if($_FILES['comment_file2']['name'] != "")
                {
            		if(!$this->upload->do_upload('comment_file2'))
            		{
            			$SystemLang['UploadError'] = array('error' => $this->upload->display_errors());
            		}
            		else
            		{
                        $SystemLang['UploadData'] = $this->upload->data();
                    }
                }
                
                //array('embed' => "@plik.jpg;type=image/jpeg")
                //array('body' => 'komentarz dodany przez API'
                
                $PostBody['body'] = $this->input->post('comment_body');
                if($this->input->post('comment_file') != "")
                {
                    $PostBody['embed'] = $this->input->post('comment_file');
                }
                
                if($SystemLang['UploadData']['full_path'] != "")
                {
                    if($SystemLang['UploadData']['image_type'] == 'gif')
                    {
                        $ImageTyle = 'image/gif';
                    }
                    elseif($SystemLang['UploadData']['image_type'] == 'png')
                    {
                        $ImageTyle = 'image/png';
                    }
                    else
                    {
                        $ImageTyle = 'image/jpeg';
                    }
                    
                    //$AttachFile['embed'] = "@".base_url($SystemLang['UploadData']['full_path']).";type=".$ImageTyle."";
                    $AttachFile['embed'] = base_url($SystemLang['UploadData']['full_path']);
                }
                
                $Result = $this->addnewcommentwykop($EntryId,$PostBody,$AttachFile);
                
                //if($Result)
                //echo '<pre>';
                //echo print_r($Result);
                //echo '</pre>';
                
                if(is_array($Result))
                {
                    $SystemLang['IsOk'] = 'ok';
                    $SystemLang['CommentId'] = $Result['id'];
                    
                    $SystemLang['Vcomment_body'] = '';
                    $SystemLang['Vcomment_file'] = '';
                }
                else
                {
                    $SystemLang['IsOk'] = 'no';
                    $SystemLang['CommentId'] = $Result;
                    
                    $SystemLang['Vcomment_body'] = $this->input->post('comment_body');
                    $SystemLang['Vcomment_file'] = $this->input->post('comment_file');
                }
            }
            else
            {
                $SystemLang['Vcomment_body'] = $this->input->post('comment_body');
                $SystemLang['Vcomment_file'] = $this->input->post('comment_file');
            }
        }
        
        $SystemLang['EntryId'] = $EntryId;
        
        $SystemLang['Title'] = ''.$this->lang->line('a1251').'';
        $SystemLang['Content'] = ''.$this->lang->line('a1252').'';
        
        $ResultFromWykop = $this->searchinsertsinwykop2($EntryId);

        if($ResultFromWykop != "")
        {
            //echo '<pre>';
            //print_r($ResultFromWykop);
            //echo '</pre>';
        }
        
        $SystemLang['Content'] .= '<br /><br /><div class="panel panel-info">
          <div class="panel-heading"><strong>'.$ResultFromWykop['0']['title'].'</strong></div>
          <div class="panel-body"><p>'.$ResultFromWykop['0']['description'].' - <a href="'.$ResultFromWykop['0']['url'].'" target="_blank">'.$this->lang->line('a1253').'</a></p></div>
        </div>
        ';
                    
        $this->load->view('head',$SystemLang);
        
        $this->load->view('wykop/addcomment2', $SystemLang);
            
        $this->load->view('foot');
    }
    
    public function graphicextension($str)
    {
        //echo 'jestem';
        if($str == "")
        {
            return true;
        }
        else
        {
            //echo 'jestem';
            $InfoFile = pathinfo($str);
            
            if($InfoFile['extension'] == 'jpg' OR $InfoFile['extension'] == 'jpeg' OR $InfoFile['extension'] == 'gif' OR $InfoFile['extension'] == 'png')
            {
                return true;
            }
            else
            {
                $this->form_validation->set_message('graphicextension', ''.$this->lang->line('a1254').'');
                return false;
            }
        }
    }
    
    public function graphicfile($str)
    {
        if($str == "")
        {
            return true;
        }
        else
        {
            $ch = curl_init($str);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_exec($ch);
            $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            // $retcode >= 400 -> not found, $retcode = 200, found.
            curl_close($ch);
    
            if($retcode == 200)
            {
                return true;
            }
            else
            {
                $this->form_validation->set_message('graphicfile', ''.$this->lang->line('a1255').'');
                return false;
            }
        }
    }
    
    public function tagsfrommicroblog()
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}

        $SystemLang['Title'] = ''.$this->lang->line('a1256').'';
        $SystemLang['Content'] = ''.$this->lang->line('a1257').'';
        
        $this->load->view('head',$SystemLang);
        
        if($this->input->post('formlogin') == 'yes')
        {
            $this->form_validation->set_rules('search_q', ''.$this->lang->line('a1258').'', 'required');
            
            if($this->form_validation->run() != FALSE)
            {
                $SearchWholeData = true;
            }
        }
        
        $SystemLang['Vsearch_q'] = $this->input->post('search_q');
                
        $this->load->view('wykop/tagsfrommicroblog', $SystemLang);
            
        if($SearchWholeData)
        {   
            $ReturnSession = $this->selectsession();
            //$ReturnSession['ValidToken'];
            //$ReturnSession['ValidKey'];
            //$ReturnSession['ValidSecret'];
                    
            if($this->IsWykopApiIn == false)
            {
                include('library/wykopapi.php');
                $this->IsWykopApiIn = true;
            }
            
            $WykopApi = new libs_Wapi($ReturnSession['ValidKey'], $ReturnSession['ValidSecret']);
            
            $ResultArray = null;
                
            $SystemLang['Comunicat'] = 'ok';
            $UserArray = $WykopResult;

            $PostFromSearch['q'] = addslashes($this->input->post('search_q'));
            
            //,$PostFromSearch
            
            $result = $WykopApi->doRequest('tag/index/'.$PostFromSearch['q'].'/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey'].'/page/1');
            
            //echo '<pre>';
            //print_r($result);
            //echo '</pre>';
            
            if ($WykopApi->isValid())
            {
                $ArrayId = 0;

                foreach ($result['items'] as $r)
                {
                    //if($r == 1)
                    //{
                        //foreach ($r as $ra)
                        //{
                            $ResultArray[$ArrayId] = $r;
                            $ArrayId++;
                        //}
                    //}
                }
            } 
            else 
            {
                $ResultError = $WykopApi->getError();
            }

            unset($WykopApi);
            
            if($ResultArray == null)
            {
                $SystemLang['ConnectProblem'] = true;
            }
            else
            {
                $SystemLang['ResultArray'] = $ResultArray;
                $SystemLang['UserArray'] = $UserArray;
            }
            
            /*if($SystemLang['NullRecords'] == true)
            {
                $SystemLang['PageContent'] = '<div class="alert alert-danger">'.$this->lang->line('a1259').'</div>';
            }
            else
            {*/
                if($SystemLang['ConnectProblem'])
                {
                    $SystemLang['PageContent'] = '<div class="alert alert-danger">'.$this->lang->line('a1260').'</div>';
                }
                else
                {
                    $SystemLang['PageContent'] = $this->wykopshowmicroblog($SystemLang['ResultArray'],$SystemLang['UserArray']);
                }
            //}
    
            
            /*echo '<pre>';
            print_r($ResultArray);
            echo '</pre>';*/
 
            $this->load->view('wykop/tagsfrommicroblog2', $SystemLang);  
        }
        
        $this->load->view('foot');
    }
    
    public function access($Action='',$SubAction='')
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        $SystemLang['Title'] = ''.$this->lang->line('a1261').'';
        $SystemLang['Content'] = ''.$this->lang->line('a1262').'';
        
        $this->load->view('head',$SystemLang);
        
        if($Action == 'delete')
        {
            $this->System_model->AccessDelete($SubAction);
            $SystemLang['ProjectDeleted'] = true;
        }
        
        if($this->input->post('formlogin') == 'yes')
        {
            $this->form_validation->set_rules('access_key', ''.$this->lang->line('a1263').'', 'required');
            $this->form_validation->set_rules('access_secret', ''.$this->lang->line('a1264').'', 'required');
            $this->form_validation->set_rules('access_connection', ''.$this->lang->line('a1265').'', 'required');
            
            if($this->form_validation->run() != FALSE)
            {
                $this->System_model->AccessAddNew();
                
                $SystemLang['Vaccess_key'] = '';
                $SystemLang['Vaccess_secret'] = '';
                $SystemLang['Vaccess_connection'] = '';
            
                $SystemLang['IsAdded'] = true;
            }
            else
            {
                $SystemLang['Vaccess_key'] = $this->input->post('access_key');
                $SystemLang['Vaccess_secret'] = $this->input->post('access_secret');
                $SystemLang['Vaccess_connection'] = $this->input->post('access_connection');
            }
        }
        
        $this->load->view('wykop/access', $SystemLang);
            
        $this->load->view('foot');
    }
    
    public function accessedit($ProjectId)
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        $SystemLang['ProjectId'] = $ProjectId;
        
        $SystemLang['Title'] = ''.$this->lang->line('a1266').'';
        $SystemLang['Content'] = ''.$this->lang->line('a1267').'';
        
        $this->load->view('head',$SystemLang);
        
        if($this->input->post('formlogin') == 'yes')
        {
            $this->form_validation->set_rules('access_key', ''.$this->lang->line('a1268').'', 'required');
            $this->form_validation->set_rules('access_secret', ''.$this->lang->line('a1269').'', 'required');
            $this->form_validation->set_rules('access_connection', ''.$this->lang->line('a1270').'', 'required');
            
            if($this->form_validation->run() != FALSE)
            {
                $this->System_model->AccessEdit($ProjectId);
                $SystemLang['IsAdded'] = true;
            }
            
            $SystemLang['Vaccess_key'] = $this->input->post('access_key');
            $SystemLang['Vaccess_secret'] = $this->input->post('access_secret');
            $SystemLang['Vaccess_connection'] = $this->input->post('access_connection');
                
        }
        else
        {
            $ResultDB = $this->System_model->AccessGetById($ProjectId);
        
            foreach($ResultDB->result() as $row)
            {
                $SystemLang['Vaccess_key'] = $row->access_key;
                $SystemLang['Vaccess_secret'] = $row->access_secret;
                $SystemLang['Vaccess_connection'] = $row->access_connection;
            }
        }
        
        $this->load->view('wykop/accessedit', $SystemLang);
        
        $this->load->view('foot');
    }
    
    public function accesscheck($AccessId)
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        $SystemLang['Title'] = ''.$this->lang->line('a1271').'';
        $SystemLang['Content'] = ''.$this->lang->line('a1272').'';
        
        $this->load->view('head',$SystemLang);
        
        $ResultDB = $this->System_model->AccessGetById($AccessId);
        
        foreach($ResultDB->result() as $row)
        {
            $AccessKey = $row->access_key;
            $AccessSecret = $row->access_secret;
            $AccessConnection = $row->access_connection;
        }
            
        //echo '<br />'.$AccessKey.'_'.$AccessSecret.'_'.$AccessConnection.'<br />';
        
        if($this->IsWykopApiIn == false)
        {
            include('library/wykopapi.php');
            $this->IsWykopApiIn = true;
        }
        
        $wapi = new libs_Wapi($AccessKey, $AccessSecret);
        $result2 = $wapi->doRequest('user/login/', array('accountkey' => $AccessConnection));
        
        //echo '<pre>';
        //print_r($result2);
        //echo '</pre>';
        
        if($result2['userkey'] != "")
        {
            $SystemLang['Comunicat'] = 'ok';
            $this->System_model->AccessSetDate($AccessId);
        }
        else
        {
            $SystemLang['Comunicat'] = $result2['userkey'];
        }
        
        $this->load->view('wykop/accesscheck', $SystemLang);
            
        $this->load->view('foot');
    }
    
    public function wykopshowlinks($ResultArray,$UserArray)
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        $ReturnValue .= '<script>
        
        function ClickItemVote(ItemId,Vote,Reason)
        {
            var msg;
            
            //alert(ItemId + Vote + Reason);
            
            $.get("'.base_url('systemid/vote').'" + "/" + ItemId + "/" + Vote + "/" + Reason, function( data ) 
            {
              msg = data;
              //$(".result").html(data);
            })
            .done(function() 
            {
                var obj = JSON.parse(msg);
                //alert("data: " + obj.votes);
                $("#votes"+ItemId).text(obj.votes);
                if(obj.votesuccess == true)
                {
                    $("#fullvote"+ItemId).hide();
                    
                    if(Vote == "minus")
                    {
                        $("#fullvoteminus"+ItemId).show();
                    }
                    
                    if(Vote == "plus")
                    {
                        $("#fullvoteplus"+ItemId).show();
                    }
                    
                    if(Vote == "cancel")
                    {
                        $("#fullvoteplus"+ItemId).hide();
                        $("#fullvoteminus"+ItemId).hide();
                        $("#fullvote"+ItemId).show();
                    }
                }
                else
                {
                    alert("'.$this->lang->line('a1273').'");
                }
            })
            .fail(function() 
            {
                alert("'.$this->lang->line('a1274').'");
            }, "json");
        }
        </script>';
        
        $ReturnValue .= '<span class="result"></span>';
        
        for($i=0;$i<count($ResultArray);$i++)
        {
            if($z==0)
            {
                $ThisClass = 'RowColor1';
                $z=1;
            }
            else
            {
                $ThisClass = 'RowColor2';
                $z=0;
            }
    
            if($ResultArray[$i]['plus18'] == 1)
            {
                $LinkPlus18 = ''.$this->lang->line('a1275').'';
            }
            else
            {
                $LinkPlus18 = ''.$this->lang->line('a1276').'';
            }
            
            if($ResultArray[$i]['can_vote'] == 1)
            {
                $LinkCanVote = ''.$this->lang->line('a1275').'';
                $UserCanVote = true;
            }
            else
            {
                $LinkCanVote = ''.$this->lang->line('a1276').'';
                $UserCanVote = false;
            }

            if($ResultArray[$i]['user_vote'] != "")
            {
                $UserCanVoteNow = false;
                $UserVoteType = $ResultArray[$i]['user_vote'];
            }
            else
            {
                $UserCanVoteNow = true;
            }
            
            if($ResultArray[$i]['user_vote'] == 'dig')
            {
                $LinkWasVoted = '<span class="bg-info">'.$this->lang->line('a1277').'</span>';
            }
            else
            {
                $LinkWasVoted = '<span class="bg-danger">'.$this->lang->line('a1278').'</span>';
            }
            
            $ReturnValue .= '<div class="row '.$ThisClass.'" style="border-top: solid 1px #b9d7e8;">
            <div class="col-md-2" style="text-align: center;"><img src="'.$ResultArray[$i]['preview'].'" /><br />';
            
            $ReturnValue .= '<br /><div class="btn btn-info btn-xs" id="votes'.$ResultArray[$i]['id'].'">'.$ResultArray[$i]['vote_count'].'</div><br />
            '.$this->lang->line('a1279').' '.$ResultArray[$i]['comment_count'].'<br />
            <br />
            ';
            
            //Głosowanie: '.$LinkCanVote.'
            
            if($UserCanVoteNow)
            {
            
            $ReturnValue .= '<script>
            var UserCanVoteOnScript = '.$UserCanVoteNow.';
            var UserVoteType = '.$UserVoteType.';
            </script>';
            

                $ReturnValue .= '<div id="fullvote'.$ResultArray[$i]['id'].'"><div onclick="ClickItemVote('.$ResultArray[$i]['id'].',\'plus\',\'empty\')" class="btn btn-xs btn-info">'.$this->lang->line('a1280').'</div>
                <div class="btn-group">
                    <button type="button" class="btn btn-xs btn-warning">'.$this->lang->line('a1281').'</button>
                    <button type="button" class="btn btn-xs btn-warning dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                    <li><a onclick="ClickItemVote('.$ResultArray[$i]['id'].',\'minus\',\'1\')">'.$this->lang->line('a1282').'</a></li>
                    <li><a onclick="ClickItemVote('.$ResultArray[$i]['id'].',\'minus\',\'2\')">'.$this->lang->line('a1283').'</a></li>
                    <li><a onclick="ClickItemVote('.$ResultArray[$i]['id'].',\'minus\',\'3\')">'.$this->lang->line('a1284').'</a></li>
                    <li><a onclick="ClickItemVote('.$ResultArray[$i]['id'].',\'minus\',\'4\')">'.$this->lang->line('a1285').'</a></li>
                    <li><a onclick="ClickItemVote('.$ResultArray[$i]['id'].',\'minus\',\'5\')">'.$this->lang->line('a1286').'</a></li>
                    </ul>
                </div>
                </div>
  
                <div id="fullvoteminus'.$ResultArray[$i]['id'].'">
                <br />'.$this->lang->line('a1287').' <span class="bg-danger">'.$this->lang->line('a1288').'</span>
                <br /><div class="btn btn-xs btn-success" onclick="ClickItemVote('.$ResultArray[$i]['id'].',\'cancel\',\'empty\')">'.$this->lang->line('a1289').'</div>
                </div>
                
                <div id="fullvoteplus'.$ResultArray[$i]['id'].'">
                <br />'.$this->lang->line('a1287').' <span class="bg-info">'.$this->lang->line('a1290').'</span>
                <br /><div class="btn btn-xs btn-success" onclick="ClickItemVote('.$ResultArray[$i]['id'].',\'cancel\',\'empty\')">'.$this->lang->line('a1289').'</div>
                </div>
                
                <script>
                $("#fullvoteminus'.$ResultArray[$i]['id'].'").hide();
                $("#fullvoteplus'.$ResultArray[$i]['id'].'").hide();
                
                if(UserCanVoteOnScript != true)
                {
                    $("#fullvote'.$ResultArray[$i]['id'].'").hide();
                    
                    if(UserVoteType == "dig")
                    {
                        $("#fullvoteplus'.$ResultArray[$i]['id'].'").hide();
                    }
                    else
                    {
                        $("#fullvoteminus'.$ResultArray[$i]['id'].'").hide();
                    }
                }
                </script>
                ';
            }
            else
            {
                $ReturnValue .= '<br />'.$this->lang->line('a1287').' '.$LinkWasVoted.'
                <br /><div class="btn btn-xs btn-success" onclick="ClickItemVote('.$ResultArray[$i]['id'].',\'cancel\',\'empty\')">'.$this->lang->line('a1289').'</div>';
            }
            
            $ReturnValue .= '</div>
            <div class="col-md-10">
            
            <h3 style="padding: 0px; margin-top: 2px; margin-bottom: 2px;"><a href="'.$ResultArray[$i]['url'].'" target="_blank">'.$ResultArray[$i]['title'].'</a></h3>
            
                <div class="row">
                <div class="col-md-2">'.$this->lang->line('a1291').'</div>
                <div class="col-md-10"><strong>'.$ResultArray[$i]['id'].'</strong></div>
                </div>
                
                <div class="row">
                <div class="col-md-2">'.$this->lang->line('a1292').'</div>
                <div class="col-md-10"><strong>'.$ResultArray[$i]['date'].'</strong></div>
                </div>
                
                <div class="row">
                <div class="col-md-2">'.$this->lang->line('a1293').'</div>
                <div class="col-md-10"><strong>'.$ResultArray[$i]['author'].'</strong></div>
                </div>
                
                <div class="row">
                <div class="col-md-2">'.$this->lang->line('a1294').'</div>
                <div class="col-md-10"><strong>'.$LinkPlus18.'</strong></div>
                </div>
                
                <div class="row">
                <div class="col-md-2">'.$this->lang->line('a1295').'</div>
                <div class="col-md-10"><strong><a href="'.$ResultArray[$i]['source_url'].'" target="_blank">'.$ResultArray[$i]['source_url'].'</a></strong></div>
                </div>
            

                <div style="padding: 5px;">'.$ResultArray[$i]['description'].'</div><br />
            ';
            
            /***********************
            *****DEBUG MODE*********
            ************************/
            
            //$ReturnValue .= '<pre>'.print_r($ResultArray[$i], true).'</pre>';
             
            $ReturnValue .= '</div>
            </div>';
        }
        
        return $ReturnValue;
    }
    
    public function vote($ItemId,$Vote,$Reason)
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        $ReturnSession = $this->selectsession();
        //$ReturnSession['ValidToken'];
        //$ReturnSession['ValidKey'];
        //$ReturnSession['ValidSecret'];
                
        if($this->IsWykopApiIn == false)
        {
            include('library/wykopapi.php');
            $this->IsWykopApiIn = true;
        }
        
        $WykopApi = new libs_Wapi($ReturnSession['ValidKey'], $ReturnSession['ValidSecret']);

        $ResultArray = null;

        $SystemLang['Comunicat'] = 'ok';
        $UserArray = $WykopResult;
        
        if($Vote == 'plus')
        {
            $result = $WykopApi->doRequest('link/dig/'.$ItemId.'/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey']);
        }
        elseif($Vote == 'minus')
        {
            $result = $WykopApi->doRequest('link/bury/'.$ItemId.'/'.$Reason.'/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey']);
        }
        elseif($Vote == 'cancel')
        {
            $result = $WykopApi->doRequest('link/cancel/'.$ItemId.'/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey']);
        }
        
        if ($WykopApi->isValid())
        {
            $ResultArray = true;
            
            $UserVote = $result['vote'];
            $UserVoteSuccess = $result['success'];
        } 
        else 
        {
            $ResultError = $WykopApi->getError();
        }

                
        unset($WykopApi);
      
        if($ResultArray == null)
        {
            //błąd połączenia
            echo json_encode(array("error"=>$ResultError));     
        }
        else
        {
            //zrobione
            echo json_encode(array("votes"=>$UserVote,'votesuccess'=>$UserVoteSuccess));     
        }
    }
        
    public function search($What)
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        if($What == 'link')
        {
            $SystemLang['Title'] = ''.$this->lang->line('a1296').'';
            $SystemLang['Content'] = ''.$this->lang->line('a1297').'';
            
            if($this->input->post('formlogin') == 'yes')
            {
                $this->form_validation->set_rules('search_q', ''.$this->lang->line('a1298').'', 'required');
                
                if($this->form_validation->run() != FALSE)
                {
                    $SearchWholeData = true;
                }
            }
            
            $SystemLang['Vsearch_q'] = $this->input->post('search_q');
            $SystemLang['Vsearch_what'] = $this->input->post('search_what');
            $SystemLang['Vsearch_sort'] = $this->input->post('search_sort');
            $SystemLang['Vsearch_when'] = $this->input->post('search_when');
        }
        
        $this->load->view('head',$SystemLang);
        
        $this->load->view('wykop/searchlink', $SystemLang);
            
        if($SearchWholeData)
        {
            $ReturnSession = $this->selectsession();
            //$ReturnSession['ValidToken'];
            //$ReturnSession['ValidKey'];
            //$ReturnSession['ValidSecret'];
            
            if($this->IsWykopApiIn == false)
            {
                include('library/wykopapi.php');
                $this->IsWykopApiIn = true;
            }
            
            $WykopApi = new libs_Wapi($ReturnSession['ValidKey'], $ReturnSession['ValidSecret']);
        
            $ResultArray = null;

            $SystemLang['Comunicat'] = 'ok';
            $UserArray = $WykopResult;

            $PostFromSearch['q'] = $this->input->post('search_q');
            $PostFromSearch['what'] = $this->input->post('search_what');
            $PostFromSearch['sort'] = $this->input->post('search_sort');
            $PostFromSearch['when'] = $this->input->post('search_when');

            $result = $WykopApi->doRequest('search/links/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey'],$PostFromSearch);
            
            if ($WykopApi->isValid())
            {
                $ArrayId = 0;

                foreach ($result as $r)
                {
                    $ResultArray[$ArrayId] = $r;
                    $ArrayId++;
                }
            } 
            else 
            {
                $ResultError = $WykopApi->getError();
            }
                
            unset($WykopApi);
            
            if($ResultArray == null)
            {
                $SystemLang['ConnectProblem'] = true;
            }
            else
            {
                $SystemLang['ResultArray'] = $ResultArray;
                $SystemLang['UserArray'] = $UserArray;
            }
            
            
            if($SystemLang['NullRecords'] == true)
            {
                $SystemLang['PageContent'] = '<div class="alert alert-danger">'.$this->lang->line('a1299').'</div>';
            }
            else
            {
                if($SystemLang['ConnectProblem'])
                {
                    $SystemLang['PageContent'] = '<div class="alert alert-danger">'.$this->lang->line('a1300').'</div>';
                }
                else
                {
                    $SystemLang['PageContent'] = $this->wykopshowlinks($SystemLang['ResultArray'],$SystemLang['UserArray']);
                }
            }
            
            $this->load->view('wykop/searchlink2', $SystemLang);  
        }
        
        $this->load->view('foot');
    }
    
    public function microblog($Type='')
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        if($Type == 'hot')
        {
            $SystemLang['Title'] = ''.$this->lang->line('a1301').'';
            $SystemLang['Content'] = ''.$this->lang->line('a1302').'';
        }
        else
        {
            $SystemLang['Title'] = ''.$this->lang->line('a1303').'';
            $SystemLang['Content'] = ''.$this->lang->line('a1304').'';
        }
        
        $this->load->view('head',$SystemLang);
        
        $ReturnSession = $this->selectsession();
        //$ReturnSession['ValidToken'];
        //$ReturnSession['ValidKey'];
        //$ReturnSession['ValidSecret'];
                
        if($this->IsWykopApiIn == false)
        {
            include('library/wykopapi.php');
            $this->IsWykopApiIn = true;
        }
        
        $WykopApi = new libs_Wapi($ReturnSession['ValidKey'], $ReturnSession['ValidSecret']);
        
        $ResultArray = null;
        
        $SystemLang['Comunicat'] = 'ok';
        $UserArray = $WykopResult;

        if($Type == 'hot')
        {
            //$result = $WykopApi->doRequest('stream/hot/userkey/'.$WykopResult['userkey'].'/appkey/'.$WykopKey[$i]['key'].'/page/1/period/12');
            $result = $WykopApi->doRequest('stream/hot/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey'].'/page/1/period/12');
        }
        else
        {
            //$result = $WykopApi->doRequest('stream/index/userkey/'.$WykopResult['userkey'].'/appkey/'.$WykopKey[$i]['key']);
            $result = $WykopApi->doRequest('stream/index/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey']);
        }
        
        if ($WykopApi->isValid())
        {
            $ArrayId = 0;

            foreach ($result as $r)
            {
                $ResultArray[$ArrayId] = $r;
                $ArrayId++;
            }
        } 
        else 
        {
            $ResultError = $WykopApi->getError();
        }

        
        if($ResultArray == null)
        {
            $SystemLang['ConnectProblem'] = true;
        }
        else
        {
            $SystemLang['ResultArray'] = $ResultArray;
            $SystemLang['UserArray'] = $UserArray;
        }
        
        if($SystemLang['NullRecords'] == true)
        {
            $SystemLang['PageContent'] = '<div class="alert alert-danger">'.$this->lang->line('a1305').'</div>';
        }
        else
        {
            if($SystemLang['ConnectProblem'])
            {
                $SystemLang['PageContent'] = '<div class="alert alert-danger">'.$this->lang->line('a1306').'</div>';
            }
            else
            {
                $SystemLang['PageContent'] = $this->wykopshowmicroblog($SystemLang['ResultArray'],$SystemLang['UserArray']);
            }
        }

        $this->load->view('wykop/microblog', $SystemLang);  
        
        $this->load->view('foot');
    }
    
    public function searchmicroblog()
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}

        $SystemLang['Title'] = ''.$this->lang->line('a1307').'';
        $SystemLang['Content'] = ''.$this->lang->line('a1308').'';
        
        $this->load->view('head',$SystemLang);
        
        if($this->input->post('formlogin') == 'yes')
        {
            $this->form_validation->set_rules('search_q', ''.$this->lang->line('a1309').'', 'required');
            
            if($this->form_validation->run() != FALSE)
            {
                $SearchWholeData = true;
            }
        }
        
        $SystemLang['Vsearch_q'] = $this->input->post('search_q');
                
        $this->load->view('wykop/searchmicroblog', $SystemLang);
            
        if($SearchWholeData)
        {
            $ReturnSession = $this->selectsession();
            //$ReturnSession['ValidToken'];
            //$ReturnSession['ValidKey'];
            //$ReturnSession['ValidSecret'];
            
            if($this->IsWykopApiIn == false)
            {
                include('library/wykopapi.php');
                $this->IsWykopApiIn = true;
            }
            
            $WykopApi = new libs_Wapi($ReturnSession['ValidKey'], $ReturnSession['ValidSecret']);
            
            $ResultArray = null;

            $SystemLang['Comunicat'] = 'ok';
            $UserArray = $WykopResult;

            $PostFromSearch['q'] = $this->input->post('search_q');
            
            $result = $WykopApi->doRequest('search/entires/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey'].'/page/1',$PostFromSearch);
            
            if ($WykopApi->isValid())
            {
                $ArrayId = 0;

                foreach ($result as $r)
                {
                    $ResultArray[$ArrayId] = $r;
                    $ArrayId++;
                }
            } 
            else 
            {
                $ResultError = $WykopApi->getError();
            }
        
            unset($WykopApi);
             
            if($ResultArray == null)
            {
                $SystemLang['ConnectProblem'] = true;
            }
            else
            {
                $SystemLang['ResultArray'] = $ResultArray;
                $SystemLang['UserArray'] = $UserArray;
            }            
            
            /*if($SystemLang['NullRecords'] == true)
            {
                $SystemLang['PageContent'] = '<div class="alert alert-danger">'.$this->lang->line('a1310').'</div>';
            }
            else
            {*/
                if($SystemLang['ConnectProblem'])
                {
                    $SystemLang['PageContent'] = '<div class="alert alert-danger">'.$this->lang->line('a1311').'</div>';
                }
                else
                {
                    $SystemLang['PageContent'] = $this->wykopshowmicroblog($SystemLang['ResultArray'],$SystemLang['UserArray']);
                }
            //}
    
            $this->load->view('wykop/searchmicroblog2', $SystemLang);  
        }
        
        $this->load->view('foot');
    }
    
    public function wykopshowmicroblog($ResultArray,$UserArray)
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        for($i=0;$i<count($ResultArray);$i++)
        {
            //echo '<pre>';
            //print_r($ResultArray[$i]);
            //echo '</pre>';
            
            if($ShowVariant == 1)
            {
                $RowColor = 'RowColor1';
                $ShowVariant = 0;   
            }
            else
            {
                $RowColor = 'RowColor2';
                $ShowVariant = 1;
            }
            
            $BodyAfterInspection = $ResultArray[$i]['description'];
            
            //$BodyAfterInspection = str_replace('<a', '<a rel="lightbox" ',$BodyAfterInspection);
            
            $BodyAfterInspection = explode(' ', $BodyAfterInspection);
            
            for($ins=0;$ins<count($BodyAfterInspection)+1;$ins++)
            {
                if(substr($BodyAfterInspection[$ins],0,1) === "#")
                {
                    $BodyAfterInspection[$ins] = '<a href="https://www.wykop.pl/tag/'.urlencode($BodyAfterInspection[$ins]).'/" target="_blank">'.$BodyAfterInspection[$ins].'</a>';
                }
            }
            
            $BodyAfterInspection = implode(' ', $BodyAfterInspection);
            
            // [[[ZMIENIONE]]]
            
            $ReturnValue .= '<div class="row '.$RowColor.'" style="padding-top: 15px; padding-botton: 15px;">
            <div class="col-md-2" style="text-align: center;">
            <img src="'.$ResultArray[$i]['author_avatar_med'].'" width="48" /><br />
            <a href="https://wykop.pl/ludzie/'.urlencode($ResultArray[$i]['author']).'" target="blank">'.$ResultArray[$i]['author'].'</a><br />
            <div class="btn btn-info btn-xs" id="votes'.$ResultArray[$i]['id'].'">'.$ResultArray[$i]['vote_count'].'</div>
            </div>
            <div class="col-md-10">
            
            '.$this->lang->line('a1312').' <strong>'.$ResultArray[$i]['id'].'</strong><br />
            '.$this->lang->line('a1313').' '.$ResultArray[$i]['date'].'<br />
            
            <div style="padding: 15px;">'.$BodyAfterInspection;
            
            if($ResultArray[$i]['embed']['type'] == 'image')
            {
                $ReturnValue .= '<br /><br /><a data-lightbox="roadtrip" href="'.$ResultArray[$i]['embed']['url'].'" target="_blank"><img src="'.$ResultArray[$i]['embed']['preview'].'" /></a>';
            }
            
            if($ResultArray[$i]['embed']['type'] == 'video')
            {
                $ReturnValue .= '<br /><br />';
                
                $ReturnValue .= '<a href="'.$ResultArray[$i]['embed']['url'].'" target="_blank"><img src="'.$ResultArray[$i]['embed']['preview'].'" /></a>';

            }
            
            $ReturnValue .= '</div>
            
            '.$this->lang->line('a1314').' <a href="'.$ResultArray[$i]['url'].'" target="_blank">'.$ResultArray[$i]['url'].'</a>
            
            
            
            </div>
            </div>';
        }
        
        //$ReturnValue .= '$(\'#overlay\').width($(window).width()).height($(window).height());';
        
        return $ReturnValue;
    }
    
    public function wykop($Type)
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        if($Type == 'promoted')
        {
            $SystemLang['Title'] = ''.$this->lang->line('a1315').'';
        }
        elseif($Type == 'upcoming')
        {
            $SystemLang['Title'] = ''.$this->lang->line('a1316').'';
        }
        elseif($Type == 'hitsyear')
        {
            $SystemLang['Title'] = ''.$this->lang->line('a1317').'';
        }
        elseif($Type == 'month')
        {
            $SystemLang['Title'] = ''.$this->lang->line('a1318').'';
        }
        elseif($Type == '2month')
        {
            $SystemLang['Title'] = ''.$this->lang->line('a1319').'';
        }
        elseif($Type == '3month')
        {
            $SystemLang['Title'] = ''.$this->lang->line('a1320').'';
        }
        
        $SystemLang['Content'] = ''.$this->lang->line('a1321').'';
        
        $this->load->view('head',$SystemLang);
            
        $ReturnSession = $this->selectsession();
        //$ReturnSession['ValidToken'];
        //$ReturnSession['ValidKey'];
        //$ReturnSession['ValidSecret'];
        
        if($this->IsWykopApiIn == false)
        {
            include('library/wykopapi.php');
            $this->IsWykopApiIn = true;
        }
        
        $WykopApi = new libs_Wapi($ReturnSession['ValidKey'], $ReturnSession['ValidSecret']);
        
        $ResultArray = null;
        
        $SystemLang['Comunicat'] = 'ok';
        $UserArray = $WykopResult;
        
        if($Type == 'promoted')
        {
            $result = $WykopApi->doRequest('links/promoted/userkey/'.$ReturnSession['ValidToken']);
        }
        elseif($Type == 'upcoming')
        {
            $result = $WykopApi->doRequest('links/upcoming/userkey/'.$ReturnSession['ValidToken']);
        }
        elseif($Type == 'hitsyear')
        {
            $result = $WykopApi->doRequest('top/index/'.date('Y').'/userkey/'.$ReturnSession['ValidToken']);
        }
        elseif($Type == 'month')
        {
            $result = $WykopApi->doRequest('top/index/'.date('Y').'/'.date('n').'/userkey/'.$ReturnSession['ValidToken']);
        }
        elseif($Type == '2month')
        {
            $result = $WykopApi->doRequest('top/index/'.date('Y').'/'.date('n', strtotime("-2 months", time())).'/userkey/'.$ReturnSession['ValidToken']);
        }
        elseif($Type == '3month')
        {
            $result = $WykopApi->doRequest('top/index/'.date('Y').'/'.date('n', strtotime("-3 months", time())).'/userkey/'.$ReturnSession['ValidToken']);
        }
        
        
        if ($WykopApi->isValid())
        {
            $ArrayId = 0;
            
            if($Type == 'hitsyear' OR $Type == 'month' OR $Type == '2month' OR $Type == '3month')
            {
                foreach ($result as $r)
                {
                    //$ResultArray[$ArrayId] = $r;
                    //$ArrayId++;
                    
                    foreach($r as $Key=>$Value)
                    {
                        $ResultArray[$ArrayId] = $Value;
                        $ArrayId++;
                    }
                }
            }
            else
            {
                foreach ($result as $r)
                {
                    $ResultArray[$ArrayId] = $r;
                    $ArrayId++;
                }
            }
        } 
        else 
        {
            $ResultError = $WykopApi->getError();
        }

        unset($WykopApi);

        if($ResultArray == null)
        {
            $SystemLang['ConnectProblem'] = true;
        }
        else
        {
            $SystemLang['ResultArray'] = $ResultArray;
            $SystemLang['UserArray'] = $UserArray;
        }
        
        
        if($SystemLang['NullRecords'] == true)
        {
            $SystemLang['PageContent'] = '<div class="alert alert-danger">'.$this->lang->line('a1322').'</div>';
        }
        else
        {
            if($SystemLang['ConnectProblem'])
            {
                $SystemLang['PageContent'] = '<div class="alert alert-danger">'.$this->lang->line('a1323').'</div>';
            }
            else
            {
                $SystemLang['PageContent'] = $this->wykopshowlinks($SystemLang['ResultArray'],$SystemLang['UserArray']);
            }
        }   

        $this->load->view('wykop/wykop', $SystemLang);
            
        $this->load->view('foot');
    }
    
    public function usedcomponents()
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        $SystemLang['Title'] = ''.$this->lang->line('a1069').'';
        $SystemLang['Content'] = ''.$this->lang->line('a1070').'';
        
        $this->load->view('head',$SystemLang);
        
        $this->load->view('components', $SystemLang);
            
        $this->load->view('foot');
    }
    
    public function settingsofscript()
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        $SystemLang['Title'] = ''.$this->lang->line('a0866').'';
        $SystemLang['Content'] = ''.$this->lang->line('a0867').'';
        
        $this->load->view('head',$SystemLang);
        
        if($this->input->post('addpage') == 'yes')
        {
            $this->form_validation->set_rules('title', ''.$this->lang->line('a0868').'', 'required');
            $this->form_validation->set_rules('root_email', ''.$this->lang->line('a0869').'', 'required|valid_email');
            //$this->form_validation->set_rules('cron', ''.$this->lang->line('a0870').'', 'required');
                        
            if($this->form_validation->run() != FALSE)
            {
                $this->System_model->UpdateConfig();
                
                $SystemLang['content_added'] = true;
                
                $this->System_model->WriteLog($this->lang->line('a0542'));
            }

            $SystemLang['Ctitle'] = $this->input->post('title');
            $SystemLang['Croot_email'] = $this->input->post('root_email');
            $SystemLang['Ccron'] = $this->input->post('cron');
        }
        else
        {
            $ConfigTable = $this->System_model->GetConfig();
            $SystemLang['Ctitle'] = $ConfigTable['title'];
            $SystemLang['Croot_email'] = $ConfigTable['root_email'];
            $SystemLang['Ccron'] = $ConfigTable['cron'];
            
        }
        
        $this->load->view('user/settings', $SystemLang);
            
        $this->load->view('foot');
    }
    
    private function IsGoodUrl($Url)
    {
        if(!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $Url))
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    
    public function isurlvalid($Url)
    {
        if(!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $Url))
        {
            $this->form_validation->set_message('isurlvalid', ''.$this->lang->line('a0883').'');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    
    public function is_this_email2($str)
    {
        $ResultDB = $this->System_model->UserCheckEmail($str);
        
        foreach($ResultDB->result() as $row)
        {
            $HowManyEmail = $row->HowMany;
        }
        
        if($HowManyEmail == 0)
		{
			$this->form_validation->set_message('is_this_email2', ''.$this->lang->line('a0884').'');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
    }
    
    public function is_valid_catpcha($str)
    {
        if($_SESSION['CatpchaWord'] == $str)
        {
            return TRUE;
        }
        else
        {
            $this->form_validation->set_message('is_valid_catpcha', ''.$this->lang->line('a0885').'');
			return FALSE;
        }
    }
    
    public function postpassword($UserId,$KeyPassword,$KeyPassword2)
    {
        $SystemLang['Title'] = ''.$this->lang->line('a0886').'';
        $SystemLang['Content'] = ''.$this->lang->line('a0887').'';
        
        $this->load->view('head',$SystemLang);
        
        $ResultDB = $this->System_model->CheckKeyPasswords($UserId,$KeyPassword,$KeyPassword2);

		foreach($ResultDB->result() as $row)
		{
            $HowManyIs = $row->HowMany;
          
            $SystemLang['change_password'] = false;
            
		    if($HowManyIs > 0)
            {                
                $this->load->helper('string');
                $TemporaryPassword = random_string('alnum', 10);
                        
                $this->System_model->ChangePasswordAutomat($UserId,$TemporaryPassword);
                
                $ResultDB4 = $this->System_model->SelectEmailContent('newpass');
                
                foreach($ResultDB4->result() as $row4)
    			{
        			 $ReadyTitle = $row4->email_title;
                     $ReadyContent = $row4->email_content;
                }
                
                $ResultDB5 = $this->System_model->GetUserDataById($UserId);
                
                foreach($ResultDB5->result() as $row5)
    			{
   			          $EmailOfShool = $row5->user_email;
                }
                
                $ReadyContent = str_replace('[new_password]',$TemporaryPassword,$ReadyContent);
                
                $DefUserDate = date('Y-m-d H:i:s');
                $DefUserIp = $_SERVER['REMOTE_ADDR'];
                
                $ReadyTitle = str_replace('[user_date]',$DefUserDate,$ReadyTitle);
                $ReadyTitle = str_replace('[user_ip]',$DefUserIp,$ReadyTitle);
                
                $ReadyContent = str_replace('[user_date]',$DefUserDate,$ReadyContent);
                $ReadyContent = str_replace('[user_ip]',$DefUserIp,$ReadyContent);
                
                $ContactAddress = $this->System_model->GetConfig();
                        
                require 'PHPMailer/PHPMailerAutoload.php';
                $mail = new PHPMailer;
    
                //$mail->SMTPDebug = 3;
                $mail->SMTPDebug = 0;
                //$mail->isSMTP();
                if($this->config->item('send_email_tls'))
                {
                    $mail->SMTPSecure = 'tls';
                }
                $mail->Host = $this->config->item('send_email_stmp_host');
                $mail->SMTPAuth = true;
                $mail->Username = $this->config->item('send_email_stmp_username');
                $mail->Password = $this->config->item('send_email_stmp_password');
                $mail->Port = $this->config->item('send_email_stmp_port');
                $mail->CharSet = 'UTF-8';
                
                $mail->FromName = $this->config->item('send_email_user_name');
                $mail->From = $ContactAddress['root_email'];
                $mail->addAddress($EmailOfShool);
                
                $mail->isHTML(false);
                
                $mail->Subject = $ReadyTitle;
                $mail->Body    = $ReadyContent;
    
                if(!$mail->send())
                {
                    $SystemLang['email_send2'] = true;
                } 
                else 
                {
                    $SystemLang['email_send'] = true;
                }
                //echo $mail->ErrorInfo;
                    
                $SystemLang['change_password'] = true;
            }
        }
        
        $this->load->view('user/postpassword', $SystemLang);
            
        $this->load->view('foot');
    }
    
    public function getpassword()
    {
        $SystemLang['Title'] = ''.$this->lang->line('a0888').'';
        $SystemLang['Content'] = ''.$this->lang->line('a0889').'';
        
        $this->load->view('head',$SystemLang);
        
        if($this->input->post('formlogin') == 'yes')
		{
			$this->form_validation->set_rules('user_email', ''.$this->lang->line('a0890').'', 'required|valid_email|callback_is_this_email2');
            $this->form_validation->set_rules('user_captcha', ''.$this->lang->line('a0891').'', 'required|callback_is_valid_catpcha');
            
			if($this->form_validation->run() != FALSE)
			{
				$ResultDB = $this->System_model->UserCheckEmailSelect($this->input->post('user_email'));

				foreach($ResultDB->result() as $row)
				{
				    $UserId = $row->user_id;
                    $UserUsername = $row->user_email;
				}

                if(empty($UserUsername))
                {
                    $SystemLang['bad_data'] = true;
                }
                else
                {
                    $SystemLang['pswd_send'] = true;
                
                    $this->load->helper('string');
                    $KeyPassword = random_string('alnum', 20);
                    $KeyPassword2 = random_string('alnum', 20);
                    
                    $this->System_model->GenerateNewPassword($UserId,$KeyPassword,$KeyPassword2);
                    
                    $ResultDB = $this->System_model->SelectGenerateNewPassword($UserId,$KeyPassword,$KeyPassword2);
            
                    foreach($ResultDB->result() as $row)
        			{
        			     $UserDate = $row->password_time;
                         $UserIp = $row->password_ip;
                    }
                    
                    $ResultDB = $this->System_model->SelectEmailContent('recpassword');
            
                    foreach($ResultDB->result() as $row)
        			{
            			 $ReadyTitle = $row->email_title;
                         $ReadyContent = $row->email_content;
                    }
                    
                    $PrepareMyLink = base_url().'generate-password/'.$UserId.'/'.$KeyPassword.'/'.$KeyPassword2;
        
                    $ReadyContent = str_replace('[change_password]',$PrepareMyLink,$ReadyContent);
        
                    $ReadyTitle = str_replace('[user_date]',$UserDate,$ReadyTitle);
                    $ReadyTitle = str_replace('[user_ip]',$UserIp,$ReadyTitle);
                    
                    $ReadyContent = str_replace('[user_date]',$UserDate,$ReadyContent);
                    $ReadyContent = str_replace('[user_ip]',$UserIp,$ReadyContent);
                    
                    $ContactAddress = $this->System_model->GetConfig();
                        
                    require 'PHPMailer/PHPMailerAutoload.php';
                    $mail = new PHPMailer;
        
                    //$mail->SMTPDebug = 3;
                    $mail->SMTPDebug = 0;
                    //$mail->isSMTP();
                    if($this->config->item('send_email_tls'))
                    {
                        $mail->SMTPSecure = 'tls';
                    }
                    $mail->Host = $this->config->item('send_email_stmp_host');
                    $mail->SMTPAuth = true;
                    $mail->Username = $this->config->item('send_email_stmp_username');
                    $mail->Password = $this->config->item('send_email_stmp_password');
                    $mail->Port = $this->config->item('send_email_stmp_port');
                    $mail->CharSet = 'UTF-8';
                    
                    $mail->FromName = $this->config->item('send_email_user_name');
                    $mail->From = $ContactAddress['root_email'];
                    $mail->addAddress($UserUsername);
                    
                    $mail->isHTML(false);
                    
                    $mail->Subject = $ReadyTitle;
                    $mail->Body    = $ReadyContent;
    
                    if(!$mail->send())
                    {
                        $SystemLang['email_send2'] = true;
                    } 
                    else 
                    {
                        $SystemLang['email_send'] = true;
                    }
                }
			}
		}
        
        $this->load->helper('captcha');
        $this->load->helper('string');
        $SystemLang['RandomString'] = random_string('alnum', 6);
                        
        $_SESSION['CatpchaWord'] = $SystemLang['RandomString'];
        
        $this->load->view('user/getpassword', $SystemLang);
            
        $this->load->view('foot');
    }
    
    public function editemail()
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        $SystemLang['Title'] = ''.$this->lang->line('a0892').'';
        $SystemLang['Content'] = ''.$this->lang->line('a0893').'';
        
        $this->load->view('head',$SystemLang);
        
        if($this->input->post('formlogin') == 'yes')
		{
            $this->form_validation->set_rules('email_title1', ''.$this->lang->line('a0894').'', 'required');
            $this->form_validation->set_rules('email_title2', ''.$this->lang->line('a0894').'', 'required');
            //$this->form_validation->set_rules('email_title3', ''.$this->lang->line('a0894').'', 'required');
            $this->form_validation->set_rules('email_content1', ''.$this->lang->line('a0895').'', 'required');
            $this->form_validation->set_rules('email_content2', ''.$this->lang->line('a0895').'', 'required');
            //$this->form_validation->set_rules('email_content3', ''.$this->lang->line('a0895').'', 'required');
            
			if($this->form_validation->run() != FALSE)
			{
                $ResultDB = $this->System_model->UpdateEmails();
                
                $SystemLang['EmailUpdated'] = true;

            }
            
            $SystemLang['Femail_title1'] = $this->input->post('email_title1');
            $SystemLang['Femail_content1'] = $this->input->post('email_content1');
            $SystemLang['Femail_title2'] = $this->input->post('email_title2');
            $SystemLang['Femail_content2'] = $this->input->post('email_content2'); 	
            $SystemLang['Femail_title3'] = $this->input->post('email_title3');
            $SystemLang['Femail_content3'] = $this->input->post('email_content3'); 	
            
            $ResultDB = $this->System_model->SelectEmail('recpassword');
            foreach($ResultDB->result() as $row)
    		{
               $SystemLang['Femail_desc1'] = $row->email_desc; 	
            }
            
            $ResultDB = $this->System_model->SelectEmail('newpass');
            foreach($ResultDB->result() as $row)
    		{
               $SystemLang['Femail_desc2'] = $row->email_desc; 	
            }
            
            $ResultDB = $this->System_model->SelectEmail('report');
            foreach($ResultDB->result() as $row)
    		{
               $SystemLang['Femail_desc3'] = $row->email_desc; 	
            }
            
        }
        else
        {
            $ResultDB = $this->System_model->SelectEmail('recpassword');
            foreach($ResultDB->result() as $row)
    		{
    		   $SystemLang['Femail_title1'] = $row->email_title; 	
               $SystemLang['Femail_content1'] = $row->email_content; 	
               $SystemLang['Femail_desc1'] = $row->email_desc; 	
            }
            
            $ResultDB = $this->System_model->SelectEmail('newpass');
            foreach($ResultDB->result() as $row)
    		{
    		   $SystemLang['Femail_title2'] = $row->email_title; 	
               $SystemLang['Femail_content2'] = $row->email_content; 	
               $SystemLang['Femail_desc2'] = $row->email_desc; 	
            }
            
            $ResultDB = $this->System_model->SelectEmail('report');
            foreach($ResultDB->result() as $row)
    		{
    		   $SystemLang['Femail_title3'] = $row->email_title; 	
               $SystemLang['Femail_content3'] = $row->email_content; 	
               $SystemLang['Femail_desc3'] = $row->email_desc; 	
            }
        }
        
        $this->load->view('user/editemail', $SystemLang);
        
        $this->load->view('foot');
    }
    
    public function changepassword()
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        $SystemLang['Title'] = ''.$this->lang->line('a0896').'';
        $SystemLang['Content'] = ''.$this->lang->line('a0897').'';
        
        $this->load->view('head',$SystemLang);
        
        if($this->input->post('formchange') == 'yes')
		{
            $this->form_validation->set_rules('user_pswd', ''.$this->lang->line('a0898').'', 'required');
			$this->form_validation->set_rules('user_pswd2', ''.$this->lang->line('a0899').'', 'required|min_length[8]|max_length[20]');
			$this->form_validation->set_rules('user_pswd3', ''.$this->lang->line('a0900').'', 'required|min_length[8]|max_length[20]|callback_checkisthesame');

			if($this->form_validation->run() != FALSE)
			{
                $ResultDB = $this->System_model->UserGetData();
                
                $PasswordMatch = false;
                
                foreach($ResultDB->result() as $row)
                {
                    if(password_verify($this->input->post('user_pswd'), $row->user_password) == false)
                    {
                        $PasswordMatch = true;
                    }
                }
                
                if($PasswordMatch)
                {
                    $SystemLang['PswdChangedError'] = true;
                }
                else
                {             
				    $this->System_model->UpdateUserPswd();
                    $SystemLang['PswdChanged'] = true;
                }
			}
		}
        
        $this->load->view('user/changepassword', $SystemLang);
        
        $this->load->view('foot');
    }
    
    public function checkisthesame($str)
    {
        if($this->input->post('user_pswd2') == $this->input->post('user_pswd3'))
        {
            return true;
        }
        else
        {
            $this->form_validation->set_message('checkisthesame', ''.$this->lang->line('a0901').'');
            return false;
        }
    }
    
    public function logout()
    {
        if($_SESSION['user_id'] == "")
        {
            redirect('');
            exit();
        }
        
        $_SESSION['user_id'] = '';
        redirect();
    }
    
    public function error404()
    {
        redirect('');
    }
    
    
    //
    // Wybieranie działąjącej sesji
    //
    
    private function SelectSessionKey()
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        $IsValidToken = false;
            
        $ResultDB = $this->System_model->AccessShowToken();
        
        foreach($ResultDB->result() as $row)
        {
            if($row->session_id != "")
            {
                $SessionTable['IsValidToken'] = true;
                $SessionTable['ValidToken'] = $row->session_ident;
                $SessionTable['ValidKey'] = $row->session_key;
                $SessionTable['ValidSecret'] = $row->session_secret;
            }
        }
        
        return $SessionTable;
    }
    
    private function DestroySessionKey()
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        $this->System_model->AccessRemoveToken();
    }
    
    private function GenerateSessionKey()
    {
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        $ResultDB = $this->System_model->AccessSelectAll();
            
        $NullRedords = 0;
        $WykopKey = null;
        $i = 0;
        
        foreach($ResultDB->result() as $row)
        {
            $WykopKey[$i]['key'] = $row->access_key;
            $WykopKey[$i]['secret'] = $row->access_secret;
            $WykopKey[$i]['connection'] = $row->access_connection;
            
            $NullRedords++;
        }
         
        $ReturnValue = false;
        
        if($NullRedords == 0)
        {
            $ReturnValue = true;
        }
        else
        {
            if($this->IsWykopApiIn == false)
            {
                include('library/wykopapi.php');
                $this->IsWykopApiIn = true;
            }
        
            $ResultArray = null;
            
            for($i=0;$i<count($WykopKey);$i++)
            {
                $WykopApi = new libs_Wapi($WykopKey[$i]['key'], $WykopKey[$i]['secret']);
                $WykopResult = $WykopApi->doRequest('user/login/', array('accountkey' => $WykopKey[$i]['connection']));
                
                //echo '<pre>';
                //print_r($WykopResult);
                //echo '</pre>';

                if($WykopResult != null)
                {
                    $this->System_model->AccessAddToken($WykopResult['userkey'],$WykopKey[$i]['key'],$WykopKey[$i]['secret']);
                    break;
                }
                
                unset($WykopApi);
            }
        }
        
        return $ReturnValue;
    }
    
    public function selectsession($MakeOption='')
    {        
        if($_SESSION['user_id'] == ""){redirect();exit();}
        
        $SessionTable = $this->SelectSessionKey();
        
        if($SessionTable['IsValidToken'] == true)
        {
            $ReturnSession['ValidToken'] = $SessionTable['ValidToken'];
            $ReturnSession['ValidKey'] = $SessionTable['ValidKey'];
            $ReturnSession['ValidSecret'] = $SessionTable['ValidSecret'];
        }
        else
        {
            $this->DestroySessionKey();
            $this->GenerateSessionKey();
            $SessionTable = $this->SelectSessionKey();
            
            $ReturnSession['ValidToken'] = $SessionTable['ValidToken'];
            $ReturnSession['ValidKey'] = $SessionTable['ValidKey'];
            $ReturnSession['ValidSecret'] = $SessionTable['ValidSecret'];
        }
        
        //echo '<pre>';
        //print_r($ReturnSession);
        //echo '</pre>';
        return $ReturnSession;
    }
}
            
?>