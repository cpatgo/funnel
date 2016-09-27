<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Preview extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'form', 'string'));
		$this->load->model(array('sitemodel', 'usermodel', 'pagemodel', 'revisionmodel'));

		$this->data['pageTitle'] = $this->lang->line('sites_page_title');

		if (!$this->ion_auth->logged_in()) {
			redirect('/login');
		}
	}


	/**
	 * Preview site
	 */
	public function index($siteID)
	{
		// Store the session ID with this session
		$this->session->set_userdata('siteID', $siteID);

		// If user is not an admin, we'll need to check of this site belongs to this user
		if ( !$this->ion_auth->is_admin() ) {
			if( !$this->sitemodel->isMine( $siteID ) ) {
				redirect('/sites');
			}
		}

		$siteData = $this->sitemodel->getSite($siteID);
		if ( $siteData == false ) {
			//site could not be loaded, redirect to /sites, with error message
			$this->session->set_flashdata('error', $this->lang->line('sites_site_error1'));
			redirect('/sites/', 'refresh');
		} else {
			$this->data['siteData'] = $siteData;

			//get page data
			$pagesData = $this->pagemodel->getPageData($siteID);
			if ( $pagesData ) {
				$this->data['pagesData'] = $pagesData;
			}

			//collect data for the image library
			$user = $this->ion_auth->user()->row();
			$userID = $user->id;
			$userImages = $this->usermodel->getUserImages( $userID );
			if ( $userImages ) {
				$this->data['userImages'] = $userImages;
			}
			$adminImages = $this->sitemodel->adminImages();
			if ( $adminImages ) {
				$this->data['adminImages'] = $adminImages;
			}

			//pre-build templates
			$pages = $this->pagemodel->getAllTemplates();

			//die( print_r($pages) );
			if ( $pages ) {
				$this->data['templates'] = $this->load->view('partials/templateframes', array('pages'=>$pages), true);
			}

			//grab all revisions
			$this->data['revisions'] = $this->revisionmodel->getForSite( $siteID, 'index' );
			$this->data['builder'] = true;
			$this->data['page'] = "site";
			$this->load->view('preview/index', $this->data);
		}
	}

	/**
	 * Get and retrieve single site data
	 * @param  integer $siteID
	 * @return [type]         [description]
	 */
	public function site($siteID)
	{
		// Store the session ID with this session
		$this->session->set_userdata('siteID', $siteID);

		// If user is not an admin, we'll need to check of this site belongs to this user
		if ( !$this->ion_auth->is_admin() ) {
			if( !$this->sitemodel->isMine( $siteID ) ) {
				redirect('/sites');
			}
		}

		$siteData = $this->sitemodel->getSite($siteID);
		if ( $siteData == false ) {
			//site could not be loaded, redirect to /sites, with error message
			$this->session->set_flashdata('error', $this->lang->line('sites_site_error1'));
			redirect('/sites/', 'refresh');
		} else {
			$this->data['siteData'] = $siteData;

			//get page data
			$pagesData = $this->pagemodel->getPageData($siteID);
			if ( $pagesData ) {
				$this->data['pagesData'] = $pagesData;
			}

			//collect data for the image library
			$user = $this->ion_auth->user()->row();
			$userID = $user->id;
			$userImages = $this->usermodel->getUserImages( $userID );
			if ( $userImages ) {
				$this->data['userImages'] = $userImages;
			}
			$adminImages = $this->sitemodel->adminImages();
			if ( $adminImages ) {
				$this->data['adminImages'] = $adminImages;
			}

			//pre-build templates
			$pages = $this->pagemodel->getAllTemplates();

			//die( print_r($pages) );
			if ( $pages ) {
				$this->data['templates'] = $this->load->view('partials/templateframes', array('pages'=>$pages), true);
			}

			//grab all revisions
			$this->data['revisions'] = $this->revisionmodel->getForSite( $siteID, 'index' );
			$this->data['builder'] = true;
			$this->data['page'] = "site";
			$this->load->view('sites/index', $this->data);
		}
	}
}
/* End of file preview.php */
/* Location: ./application/controllers/preview.php */