<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//exit();
/**
 * @author Lukasz Sosna
 * @copyright 2019
 * @e-mail tree@interia.pl
 * @e-mail support@phpbluedragon.eu
 * @www http://phpbluedragon.eu
 */
 
set_time_limit(300);
class Cronid extends CI_Controller
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
    
    public function index($Action='',$SubAction="")
    {  
        //
        // Sprawdź czy już ustawione daty dla CRON'a
        //
        
        //echo date('Y-m-d H:i:s', '1498139111');
        
        $ResultDB = $this->System_model->CronQueryAboutDate();
        
        $IsDate = false;
        
        foreach($ResultDB->result() as $row)
        {
            $IsDate = $row->date_id;
        }
            
        if(!$IsDate)
        {
            $this->System_model->CronInsertDate();
        }
        
        //
        // WykopAPI
        //
        
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
        
        //
        // Ustawienia CRON'a
        //
        $ResultDB = $this->System_model->CronSelectSetup();
            
        foreach($ResultDB->result() as $row)
        {
            $Vcron_top = $row->cron_top;
            $Vcron_dig = $row->cron_dig;
            $Vcron_microblog = $row->cron_microblog;
            $Vcron_comm_top = $row->cron_comm_top;
            $Vcron_comm_howmany_top = $row->cron_comm_howmany_top;
            $Vcron_comm_dig = $row->cron_comm_dig;
            $Vcron_comm_howmany_dig = $row->cron_comm_howmany_dig;
            $Vcron_start = $row->cron_start;
            $Vcron_stop = $row->cron_stop;
        }
                
        //
        // Wybieranie zadań CRON
        //

        for($i=0;$i<250;$i++)
        {
            //echo $ReturnSession['ValidToken'].' - '.$ReturnSession['ValidKey'].' - '.$ReturnSession['ValidSecret'];
            
            /**********************************************************************************************************
            *********** Niebezpiecznie jest dodawać komentarze - można oberwać Perma więc usunałem*********************
            ***********************************************************************************************************/
            
            /*
            $ResultDB = $this->System_model->CronSelectJobsToDo();
            
            foreach($ResultDB->result() as $row)
            {
                $PostBody['body'] = $row->entry_comment;
                
                if($row->entry_file != "")
                {
                    $PostBody['embed'] = $row->entry_file;
                }
                    
                $this->addnewentrywykop($PostBody);
                
                $this->System_model->CronSetJobsAddMaked($row->entry_id);
            }
            */
        
            //
            // Wybieranie dat dla poszczególnych opcji w CRON
            //
            
            $ResultDB = $this->System_model->CronQueryAfterDate();
            
            $Time_top = false;
            $Time_dig = false;
            $Time_microblog = false;
            $Time_comm_top = false;
            $Time_comm_dig = false;
            
            foreach($ResultDB->result() as $row)
            {
                if($row->date_what == 'cron_top'){$Time_top = true;$Time_topID = $row->date_id;}
                if($row->date_what == 'cron_dig'){$Time_dig = true;$Time_digID = $row->date_id;}
                if($row->date_what == 'cron_microblog'){$Time_microblog = true;$Time_microblogID = $row->date_id;}
                if($row->date_what == 'cron_comm_top'){$Time_comm_top = true;$Time_comm_topID = $row->date_id;}
                if($row->date_what == 'cron_comm_dig'){$Time_comm_dig = true;$Time_comm_digID = $row->date_id;}
            }

            //
            // Plus/Minus dla wpisów (top)
            //
            
            if($Time_top)
            {
                if($Vcron_top == 'plus' OR $Vcron_top == 'minus')
                {
                    $result = $WykopApi->doRequest('links/promoted/userkey/'.$ReturnSession['ValidToken']);
                    
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
                    
                    foreach($ResultArray as $RowItem)
                    {
                        $RowItem['UserWasWoted'] = null;
                        $ItemId = $RowItem['id'];
                        
                        if($Vcron_top == 'plus')
                        {
                            $result = $WykopApi->doRequest('link/dig/'.$ItemId.'/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey']);
                        }
                        else
                        {
                            $result = $WykopApi->doRequest('link/bury/'.$ItemId.'/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey']);
                        }
                    }
                    
                    $this->System_model->QueryInCronUpdateDate($Time_topID);
                }
            }
            
            //
            // Plus/Minus dla wpisów (wykopalisko)
            //
            
            if($Time_dig)
            {
                if($Vcron_dig == 'plus' OR $Vcron_dig == 'minus')
                {
                    $result = $WykopApi->doRequest('links/upcoming/userkey/'.$ReturnSession['ValidToken']);
                    
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
                    
                    foreach($ResultArray as $RowItem)
                    {
                        $RowItem['UserWasWoted'] = null;
                        $ItemId = $RowItem['id'];
                        
                        if($Vcron_dig == 'plus')
                        {
                            $result = $WykopApi->doRequest('link/dig/'.$ItemId.'/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey']);
                        }
                        else
                        {
                            $result = $WykopApi->doRequest('link/bury/'.$ItemId.'/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey']);
                            
                        }
                    }
                    
                    $this->System_model->QueryInCronUpdateDate($Time_digID);
                }
            }
            
            //
            // Polubienia - Mikroblog
            //
            
            if($Time_microblog)
            {
                if($Vcron_microblog == 6 OR $Vcron_microblog == 12 OR $Vcron_microblog == 24)
                {
                    $result = $WykopApi->doRequest('stream/hot/userkey/'.$ReturnSession['ValidToken'].'/page/1/period/'.$Vcron_microblog);
        
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
                    
                    foreach($ResultArray as $RowItem)
                    {
                        $RowItem['UserWasWoted'] = false;
        
                        $ItemId = $RowItem['id'];
                        
                        if($RowItem['user_vote'] == null)
                        {
                            $result = $WykopApi->doRequest('entries/vote/entry/'.$ItemId.'/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey']);
                            //Entries
                            if (!$WykopApi->isValid())
                            {
                                $ResultError = $WykopApi->getError();
                            }
                        }
                        else
                        {
                            $RowItem['UserWasWoted'] = true;
                        }  
                    }
                    
                    $this->System_model->QueryInCronUpdateDate($Time_microblogID);
                }
            }
            
            //
            // Plus/Minus dla komentarzy (top)
            //
            
            if($Time_comm_top)
            {
                if($Vcron_comm_top == 'plus' OR $Vcron_comm_top == 'minus' OR $Vcron_comm_top == 'like')
                {
                    $result = $WykopApi->doRequest('links/promoted/userkey/'.$ReturnSession['ValidToken']);
                    
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
                    
                    $PozitionNumber = $Vcron_comm_howmany_top;
                    
                    if(!is_numeric($PozitionNumber)){$PozitionNumber = 1;}
                    
                    if($PozitionNumber == 0){$PozitionNumber = 1;}
                    
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
                                
                                $MyVoteIs = '';
                                
                                if($CommPoz == $PozitionNumber)
                                {
                                    if($Vcron_comm_top == 'plus')
                                    {
                                        $result = $WykopApi->doRequest('comments/plus/'.$ItemId.'/'.$r2['id'].'/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey']);
                                        $MyVoteIs = '+';
                                    }
                                    elseif($Vcron_comm_top == 'minus')
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
                    
                    $this->System_model->QueryInCronUpdateDate($Time_comm_topID);
                }
            }
            
            //
            // Plus/Minus dla komentarzy b(wykopalisko)
            //
            
            if($Time_comm_dig)
            {
                if($Vcron_comm_dig == 'plus' OR $Vcron_comm_dig == 'minus' OR $Vcron_comm_dig == 'like')
                {
                    $result = $WykopApi->doRequest('links/upcoming/userkey/'.$ReturnSession['ValidToken']);
                    
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
                    
                    $PozitionNumber = $Vcron_comm_howmany_dig;
                    
                    if(!is_numeric($PozitionNumber)){$PozitionNumber = 1;}
                    
                    if($PozitionNumber == 0){$PozitionNumber = 1;}
                    
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
                                
                                $MyVoteIs = '';
                                
                                if($CommPoz == $PozitionNumber)
                                {
                                    if($Vcron_comm_dig == 'plus')
                                    {
                                        $result = $WykopApi->doRequest('comments/plus/'.$ItemId.'/'.$r2['id'].'/userkey/'.$ReturnSession['ValidToken'].'/appkey/'.$ReturnSession['ValidKey']);
                                        $MyVoteIs = '+';
                                    }
                                    elseif($Vcron_comm_dig == 'minus')
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
                    
                    $this->System_model->QueryInCronUpdateDate($Time_comm_digID);
                }
            }
            
            sleep(1);
        }
    }
    
    protected function addnewentrywykop($PostBody,$AttachFile='')
    {        
        if($_SESSION['user_id'] == ""){redirect();exit();}
                
        /*
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
        $PostBody);
        
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
        */
    }
    
    //
    // Wybieranie działającej sesji
    //
    
    private function SelectSessionKey()
    {
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
        $this->System_model->AccessRemoveToken();
    }
    
    private function GenerateSessionKey()
    {
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