<?php namespace App\Controllers;

class Test extends BaseController
{
	public function index()
	{
        return $this->respond(null,405);
	}

	//--------------------------------------------------------------------

    public function addAppointmentOrRequest(){
	    $this->respond("Wrong Way, Go Back",400);
    }

    public function readAppointmentOrRequest(){
        $this->respond("Wrong Way, Go Back",400);
    }

    public function listAppointmentOrRequest(){
        $this->respond("Wrong Way, Go Back",400);
    }

    public function updateAppointmentOrRequest(){
        $this->respond("Wrong Way, Go Back",400);
    }

    public function cancelAppointmentOrRequest(){
        $this->respond("Wrong Way, Go Back",400);
    }
    #---------------Lab Staff Permission Needed for Following Methods--------------------------
    public function releaseResult(){
        $this->respond("Wrong Way, Go Back",400);
    }
}
