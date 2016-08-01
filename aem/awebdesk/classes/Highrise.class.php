<?php

/**
 * Project:     Highrise PHP API
 * File:        Highrise.class.php
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * For questions, help, comments, discussion, etc., please join the
 * Smarty mailing list. Send a blank e-mail to
 * smarty-discussion-subscribe@googlegroups.com
 *
 * @link http://www.rebel-interactive.com/
 * @copyright 2009 REBEL INTERACTIVE, Inc.
 * @author Monte Ohrt <monte at ohrt dot com>
 * @package BasecampPHPAPI
 * @version 1.1.1-dev
 */

/* $Id$ */

/*
 * The Basecamp PHP API requires the RestRequest and RestBuilder library classes, which
 * comes bundled with this library. RestRequest/RestBuilder can easily be used for
 * other PHP REST projects.
 */


require(awebdesk('rest/RestBuilder.class.php'));

class Highrise extends RestBuilder {

  /* public methods */

  /**
   * get info for logged in user
   *
   * @param string $format format of response (opt)
   * @return array response content
   */
  public function getMe($format=null) {
    return $this->processRequest("{$this->baseurl}me.xml","GET",$format);
  }

  /**
   * get id for logged in user
   *
   * @return array response content
   */
  public function getMyId() {
    $response = $this->getMe($format='simplexml');
    return (int) $response['body']->id;
  }

  /**
   * get all people
   *
   */
  public function getPeople() {
    return $this->processRequest("{$this->baseurl}people.xml","GET");
  }

  /**
   * get all task categories
   *
   */
  public function getTaskCategories($format=null) {
    return $this->processRequest("{$this->baseurl}task_categories.xml","GET",$format);
  }

  /**
   * get all deal categories
   *
   */
  public function getDealCategories($format=null) {
    return $this->processRequest("{$this->baseurl}deal_categories.xml","GET",$format);
  }

  /**
   * get all people for a company
   *
   * @param int $company_id
   * @param string $format format of response (opt)
   * @return array response content
   */
  public function getPeopleForCompany($company_id,$format=null) {
    if(!preg_match('!^\d+$!',$company_id))
      throw new InvalidArgumentException("company id must be a number.");
    return $this->processRequest("{$this->baseurl}companies/{$company_id}/people.xml","GET",$format);
  }

  /**
   * get all people for a search term
   *
   * @param string $term search term
   * @param string $format format of response (opt)
   * @return array response content
   */
  public function getPeopleSearchResults($term,$format=null) {
    if(empty($term))
      throw new InvalidArgumentException("search term must be a provided.");
    $term = urlencode($term);
    return $this->processRequest("{$this->baseurl}people/search.xml?term={$term}","GET",$format);
  }
	
	/* duplicate of function above, but searching more fields than just name */
  public function getPeopleSearchCriteriaResults($terms,$format=null) {
    if(!$terms)
      throw new InvalidArgumentException("search term(s) must be a provided.");
		$search_array = array();
		foreach ($terms as $field => $value) {
			$search_array[] = "criteria[" . $field . "]=" . urlencode($value);
		}
    return $this->processRequest("{$this->baseurl}people/search.xml?" . implode("&", $search_array),"GET",$format);
  }

  /**
   * get array of ids for all people in a company
   *
   * @param int $company_id
   * @return array ids of people
   */
  public function getPeopleIdsForCompany($company_id) {
    $response = $this->getPeopleForCompany($company_id,'simplexml');

    $ids = array();
    foreach($response['body']->person as $person) {
      $ids[] = (int)$person->id;
    }

    return $ids;
  }

  /**
   * get a person
   *
   * @param int $person_id
   * @return array response content
   */
  public function getPerson($person_id) {
    if(!preg_match('!^\d+$!',$person_id))
      throw new InvalidArgumentException("person id must be a number.");
    return $this->processRequest("{$this->baseurl}people/{$person_id}.xml","GET");
  }

  /**
   * create a person
   *
   * @param string $first_name the new person first name
   * @param string $last_name the last name
   * @param array $emails the list of email addresses (email => type[work|personal|other])
   * @param array $phones the list of email addresses (email => type[work|personal|other])
   * possible values:
   * <ul>
   *   <li>post</li>
   *   <li>attachment</li>
   * </ul>
   * @return array response content
   */
  public function createPerson($first_name,$last_name,$notes,$emails=array(),$phones=array()) {
    if(empty($first_name))
      throw new InvalidArgumentException("First name cannot be empty.");
    if(empty($last_name))
      throw new InvalidArgumentException("Last name cannot be empty.");
    if(!is_array($emails))
      throw new InvalidArgumentException("'Emails' should be a list of emails.");
    if(!is_array($phones))
      throw new InvalidArgumentException("'Emails' should be a list of emails.");

    $email_addresses = array();
    foreach ( $emails as $k => $v ) {
    	$email_addresses['email-address:'.count($email_addresses)] = array(
    		'address' => $k,
    		'location' => $v,
    	);
    }
    $phone_numbers = array();
    foreach ( $phones as $k => $v ) {
    	$phone_numbers['phone-number:'.count($email_addresses)] = array(
    		'number' => $k,
    		'location' => $v,
    	);
    }
    $body = array(
              'person'=>array(
                'first-name'=>$first_name,
                'last-name'=>$last_name,
                'title'=>'some title',
                'company-name'=>'some company',
                'background'=>$notes,
                'contact-data'=>array(
                  'email-addresses'=>$email_addresses,
                  'phone-numbers'=>$phone_numbers,
                ),
              )
            );

    $this->setupRequestBody($body);
    $response = $this->processRequest("{$this->baseurl}people.xml","POST");
    // set new person id
    if(preg_match('!(\d+)\.xml!',$response['location'],$match))
      $response['id'] = $match[1];
    else
      $response['id'] = null;
    return $response;
  }

  /**
   * gets all companies
   *
   * @return array response content
   */
  public function getCompanies() {
    return $this->processRequest("{$this->baseurl}companies.xml","GET");
  }

  /**
   * get a single company
   *
   * @param int $company_id
   * @return array response content
   */
  public function getCompany($company_id) {
    if(!preg_match('!^\d+$!',$company_id))
      throw new InvalidArgumentException("company id must be a number.");
    return $this->processRequest("{$this->baseurl}companies/{$company_id}.xml","GET");
  }

  /**
   * get a single task category
   *
   * @param int $category_id
   * @return array response content
   */
  public function getTaskCategory($category_id) {
    if(!preg_match('!^\d+$!',$category_id))
      throw new InvalidArgumentException("category id must be a number.");
    return $this->processRequest("{$this->baseurl}task_categories/{$category_id}.xml","GET");
  }

  /**
   * get a single deal category
   *
   * @param int $category_id
   * @return array response content
   */
  public function getDealCategory($category_id) {
    if(!preg_match('!^\d+$!',$category_id))
      throw new InvalidArgumentException("category id must be a number.");
    return $this->processRequest("{$this->baseurl}deal_categories/{$category_id}.xml","GET");
  }

  /**
   * create a category for a task
   *
   * @param string $category_name the new category name
   * @param string $type the category type
   * possible values:
   * <ul>
   *   <li>post</li>
   *   <li>attachment</li>
   * </ul>
   * @return array response content
   */
  public function createTaskCategory($category_name,$type='post') {
    if(empty($category_name))
      throw new InvalidArgumentException("category name cannot be empty.");
  	$type = strtolower($type);
    if(!in_array($type,array('post','attachment')))
      throw new InvalidArgumentException("'{$type}' is an invalid category type.");

    $body = array(
              'task-category'=>array(
                //'type'=>$type,
                'name'=>$category_name
                )
            );

    $this->setupRequestBody($body);
    $response = $this->processRequest("{$this->baseurl}task_categories.xml","POST");
    // set new category id
    if(preg_match('!(\d+)\.xml!',$response['location'],$match))
      $response['id'] = $match[1];
    else
      $response['id'] = null;
    return $response;
  }

  /**
   * update a task category name
   *
   * @param int $category_id
   * @param string $category_name the new category name
   * @return array response content
   */
  public function updateTaskCategoryName($category_id,$category_name) {
    if(!preg_match('!^\d+$!',$category_id))
      throw new InvalidArgumentException("category id must be a number.");
    if(empty($category_name))
      throw new InvalidArgumentException("'{$category_name}' cannot be empty.");

    $body = array(
              'task-category'=>array(
                'name'=>$category_name
                )
            );

    $this->setupRequestBody($body);
    return $this->processRequest("{$this->baseurl}task_categories/{$category_id}.xml","PUT");
  }

  /**
   * delete a task category
   *
   * @param int $category_id
   * @return array response content
   */
  public function deleteTaskCategory($category_id) {
    if(!preg_match('!^\d+$!',$category_id))
      throw new InvalidArgumentException("category id must be a number.");
    return $this->processRequest("{$this->baseurl}task_categories/{$category_id}.xml","DELETE");
  }

  /**
   * create a category for a deal
   *
   * @param string $category_name the new category name
   * @param string $type the category type
   * possible values:
   * <ul>
   *   <li>post</li>
   *   <li>attachment</li>
   * </ul>
   * @return array response content
   */
  public function createDealCategory($category_name,$type='post') {
    if(empty($category_name))
      throw new InvalidArgumentException("category name cannot be empty.");
  	$type = strtolower($type);
    if(!in_array($type,array('post','attachment')))
      throw new InvalidArgumentException("'{$type}' is an invalid category type.");

    $body = array(
              'deal-category'=>array(
                //'type'=>$type,
                'name'=>$category_name
                )
            );

    $this->setupRequestBody($body);
    $response = $this->processRequest("{$this->baseurl}deal_categories.xml","POST");
    // set new category id
    if(preg_match('!(\d+)\.xml!',$response['location'],$match))
      $response['id'] = $match[1];
    else
      $response['id'] = null;
    return $response;
  }

  /**
   * update a deal category name
   *
   * @param int $category_id
   * @param string $category_name the new category name
   * @return array response content
   */
  public function updateDealCategoryName($category_id,$category_name) {
    if(!preg_match('!^\d+$!',$category_id))
      throw new InvalidArgumentException("category id must be a number.");
    if(empty($category_name))
      throw new InvalidArgumentException("'{$category_name}' cannot be empty.");

    $body = array(
              'deal-category'=>array(
                'name'=>$category_name
                )
            );

    $this->setupRequestBody($body);
    return $this->processRequest("{$this->baseurl}deal_categories/{$category_id}.xml","PUT");
  }

  /**
   * delete a deal category
   *
   * @param int $category_id
   * @return array response content
   */
  public function deleteDealCategory($category_id) {
    if(!preg_match('!^\d+$!',$category_id))
      throw new InvalidArgumentException("category id must be a number.");
    return $this->processRequest("{$this->baseurl}deal_categories/{$category_id}.xml","DELETE");
  }

  /**
   * get tasks for a resource
   *
   * @param string $resource_type
   * possible values:
   * <ul>
   *   <li>people</li>
   *   <li>companies</li>
   *   <li>kases</li>
   *   <li>deals</li>
   * </ul>
   * @param int $resource_id
   * @return array response content
   */
  public function getTasksForResource($resource_type,$resource_id=null) {
  	$resource_type = strtolower($resource_type);
    if(!in_array($resource_type,array('people','companies','kases','deals')))
      throw new InvalidArgumentException("'{$resource_type}' is an invalid resource type.");
    if(!preg_match('!^\d+$!',$resource_id))
      throw new InvalidArgumentException("resource id must be a number.");

    return $this->processRequest("{$this->baseurl}{$resource_type}/{$resource_id}/tasks.xml","GET");
  }

  /**
   * get tasks for a person
   *
   * @return array response content
   */
  public function getTasksForPerson($person_id) {
    return $this->getTasksForResource('people',$person_id);
  }

  /**
   * get tasks for a company
   *
   * @return array response content
   */
  public function getTasksForCompany($company_id) {
    if(!preg_match('!^\d+$!',$company_id))
      throw new InvalidArgumentException("company id must be a number.");
    return $this->getTasksForResource('companies',$company_id);
  }

  /**
   * get tasks for a kase
   *
   * @return array response content
   */
  public function getTasksForCase($case_id) {
    if(!preg_match('!^\d+$!',$case_id))
      throw new InvalidArgumentException("case id must be a number.");
    return $this->getTasksForResource('kases',$company_id);
  }

  /**
   * get tasks for a deal
   *
   * @return array response content
   */
  public function getTasksForDeal($deal_id) {
    if(!preg_match('!^\d+$!',$deal_id))
      throw new InvalidArgumentException("deal id must be a number.");
    return $this->getTasksForResource('deals',$company_id);
  }

  /**
   * get tasks list (filtered)
   *
   * @param string $filter_type
   * possible values:
   * <ul>
   *   <li>upcoming</li>
   *   <li>assigned</li>
   *   <li>completed</li>
   * </ul>
   * @return array response content
   */
  public function getTasksFiltered($filter_type='upcoming',$format=null) {
  	$filter_type = strtolower($filter_type);
    if(!in_array($filter_type,array('upcoming','assigned','completed')))
      throw new InvalidArgumentException("'{$filter_type}' is an invalid filter type.");

    return $this->processRequest("{$this->baseurl}tasks{$filter_type}.xml","GET",$format);
  }

  /**
   * get array of ids for all tasks
   *
   * @param string $filter_type
   * possible values:
   * <ul>
   *   <li>upcoming</li>
   *   <li>assigned</li>
   *   <li>completed</li>
   * </ul>
   * @param string $format format of response (opt)
   * @return array response content
   */
  public function getTaskIdsFiltered($filter_type='upcoming') {
    $response = $this->getTasksFiltered($filter_type,'simplexml');

    $ids = array();
    foreach($response['body']->task as $list) {
      $ids[] = (int)$list->id;
    }

    return $ids;
  }

  /**
   * get a single task
   *
   * @param int $item_id
   * @return array response content
   */
  public function getTask($item_id) {
    if(!preg_match('!^\d+$!',$item_id))
      throw new InvalidArgumentException("item id must be a number.");
    return $this->processRequest("{$this->baseurl}tasks/{$item_id}.xml","GET");
  }

  /**
   * complete a task
   *
   * @param int $item_id
   * @return array response content
   */
  public function completeTask($item_id) {
    if(!preg_match('!^\d+$!',$item_id))
      throw new InvalidArgumentException("item id must be a number.");
    return $this->processRequest("{$this->baseurl}tasks/{$item_id}/complete.xml","PUT");
  }

  /**
   * uncomplete a task
   *
   * @param int $item_id
   * @return array response content
   */
  public function uncompleteTask($item_id) {
    if(!preg_match('!^\d+$!',$item_id))
      throw new InvalidArgumentException("item id must be a number.");
    return $this->processRequest("{$this->baseurl}tasks/{$item_id}/uncomplete.xml","PUT");
  }

  /**
   * creates a task
   *
   * @param string $content task content
   * @param string $responsible_party_type
   * possible values:
   * <ul>
   *   <li>person</li>
   *   <li>company</li>
   *   <li>case</li>
   *   <li>deal</li>
   * </ul>
   * @param int $responsible_party_id
   * @param bool $notify send notifications?
   * @return array response content
   */
  public function createTask(
    $content,
    $responsible_party_type=null,
    $responsible_party_id=null,
    $notify=null
    ) {
    if(empty($content))
      throw new InvalidArgumentException("task content content cannot be empty.");
  	$responsible_party_type = strtolower($responsible_party_type);
    if(!empty($responsible_party_type) && !in_array($responsible_party_type,array('person','company','case','deal')))
      throw new InvalidArgumentException("'{$responsible_party_type}' is not a valid subject type.");
    if(!empty($responsible_party_type) && empty($responsible_party_id))
      throw new InvalidArgumentException("subject party id cannot be empty.");

    if($responsible_party_type == 'person'){
      $resp_party = $responsible_party_id;
      $resp_party_type = 'Party';
    }elseif($responsible_party_type == 'company'){
      $resp_party = $responsible_party_id;
      $resp_party_type = 'Company';
    }elseif($responsible_party_type == 'case'){
      $resp_party = $responsible_party_id;
      $resp_party_type = 'Kase';
    }elseif($responsible_party_type == 'deal'){
      $resp_party = $responsible_party_id;
      $resp_party_type = 'Deal';
    }else{
      $resp_party = '';
      $resp_party_type = '';
    }

    $data = array(
              'task'=>array(
                'body'=>$content,
                'frame'=>'today',
                'subject-type'=>$resp_party_type,
                'subject-id'=>$resp_party,
                'notify type="boolean"'=>$notify
                )
            );

    $this->setupRequestBody($data);
    //$xml = new SimpleXMLElement($this->getRequestBody());dbg($xml);
    //dbg($this->getRequestBody(),1);

    $response = $this->processRequest("{$this->baseurl}tasks.xml","POST");
    // set new list id
    if(preg_match('!(\d+)$!',$response['location'],$match))
      $response['id'] = $match[1];
    else
      $response['id'] = null;
    return $response;
  }

  /**
   * updates a task
   *
   * @param int $task_id
   * @param string $content task item content
   * @param string $responsible_party_type
   * possible values:
   * <ul>
   *   <li>person</li>
   *   <li>company</li>
   * </ul>
   * @param int $responsible_party_id
   * @param bool $notify send notifications?
   * @return array response content
   */
  public function updateTask(
    $task_id,
    $content,
    $responsible_party_type=null,
    $responsible_party_id=null,
    $notify=null
    ) {
    if(!preg_match('!^\d+$!',$task_id))
      throw new InvalidArgumentException("task id must be a number.");
    if(empty($content))
      throw new InvalidArgumentException("task content cannot be empty.");
  	$responsible_party_type = strtolower($responsible_party_type);
    if(isset($responsible_party_type) && !in_array($responsible_party_type,array('person','company','case','deal')))
      throw new InvalidArgumentException("'{$responsible_party_type}' is not a valid subject party type.");
    if(!empty($responsible_party_type) && empty($responsible_party_id))
      throw new InvalidArgumentException("subject party id cannot be empty.");

    if($responsible_party_type == 'person'){
      $resp_party = $responsible_party_id;
      $resp_party_type = 'Party';
    }elseif($responsible_party_type == 'company'){
      $resp_party = $responsible_party_id;
      $resp_party_type = 'Company';
    }elseif($responsible_party_type == 'case'){
      $resp_party = $responsible_party_id;
      $resp_party_type = 'Kase';
    }elseif($responsible_party_type == 'deal'){
      $resp_party = $responsible_party_id;
      $resp_party_type = 'Deal';
    }else{
      $resp_party = '';
      $resp_party_type = '';
    }

    $data = array(
              'task'=>array(
                'content'=>$content,
                'subject-type'=>$resp_party_type,
                'subject-id'=>$resp_party,
                'notify type="boolean"'=>$notify
                )
            );

    $this->setupRequestBody($data);

    return $this->processRequest("{$this->baseurl}tasks/{$task_id}.xml","PUT");
  }

  /**
   * deletes a task
   *
   * @param int $task_id
   * @return array response content
   */
  public function deleteTask($task_id) {
    if(!preg_match('!^\d+$!',$task_id))
      throw new InvalidArgumentException("task id must be a number.");
    return $this->processRequest("{$this->baseurl}tasks/{$task_id}.xml","DELETE");
  }

  /**
   * get time entries for a task
   *
   * @param int $task_id
   * @param int $page
   * @return array response content
   */
  public function getTimeEntriesForTask($task_id,$page=0) {
    if(!preg_match('!^\d+$!',$task_id))
      throw new InvalidArgumentException("task id must be a number.");
    $response = $this->processRequest("{$this->baseurl}tasks/{$task_id}/time_entries.xml?page={$page}","GET");
    if(preg_match('!X-Records: (\d+)!',$response['headers'],$match))
    $response['records'] = $match[1];
    if(preg_match('!X-Pages: (\d+)!',$response['headers'],$match))
    $response['pages'] = $match[1];
    if(preg_match('!X-Page: (\d+)!',$response['headers'],$match))
    $response['page'] = $match[1];

    return $response;
  }

  /**
   * create a time tracking entry for a person
   *
   * @param int $person_id person time entry is for
   * @param string $date date in format YYYY-MM-DD
   * @param string $hours
   * @param string $description
   * @return array response content
   */
  public function createTimeEntryForPerson(
    $person_id,
    $date,
    $hours,
    $description=null) {
    if(!preg_match('!^\d+$!',$person_id))
      throw new InvalidArgumentException("person id must be a number.");
    if(empty($date))
      throw new InvalidArgumentException("date cannot be empty.");
    if(empty($hours))
      throw new InvalidArgumentException("hours cannot be empty.");

    // if date is not in correct format, try to reformat it
    if(!preg_match('!^\d{4}-\d{2}-\d{2}$!',$date))
      $date = strftime('%Y-%m-%d',strtotime($date));

    $data = array(
              'time-entry'=>array(
                'person-id'=>$person_id,
                'date'=>$date,
                'hours'=>$hours,
                'description'=>$description
                )
            );

    $this->setupRequestBody($data);

    $response = $this->processRequest("{$this->baseurl}/people/{$person_id}/time_entries.xml","POST");
    // set new time entry id
    if(preg_match('!(\d+)$!',$response['location'],$match))
      $response['id'] = $match[1];
    else
      $response['id'] = null;

    return $response;

  }

  /**
   * create a time tracking entry for a task
   *
   * @param int $task_id
   * @param int $person_id person time entry is for
   * @param string $date date in format YYYY-MM-DD
   * @param string $hours
   * @param string $description
   * @return array response content
   */
  public function createTimeEntryForTaskItem(
    $task_id,
    $person_id,
    $date,
    $hours,
    $description=null) {
    if(!preg_match('!^\d+$!',$task_id))
      throw new InvalidArgumentException("task id must be a number.");
    if(!preg_match('!^\d+$!',$person_id))
      throw new InvalidArgumentException("person id must be a number.");
    if(empty($date))
      throw new InvalidArgumentException("date cannot be empty.");
    if(empty($hours))
      throw new InvalidArgumentException("hours cannot be empty.");

    // if date is not in correct format, try to reformat it
    if(!preg_match('!^\d{4}-\d{2}-\d{2}$!',$date))
      $date = strftime('%Y-%m-%d',strtotime($date));

    $data = array(
              'time-entry'=>array(
                'person-id'=>$person_id,
                'date'=>$date,
                'hours'=>$hours,
                'description'=>$description
                )
            );

    $this->setupRequestBody($data);

    $response = $this->processRequest("{$this->baseurl}/tasks/{$task_id}/time_entries.xml","POST");
    // set new time entry id
    if(preg_match('!(\d+)$!',$response['location'],$match))
      $response['id'] = $match[1];
    else
      $response['id'] = null;

    return $response;

  }

  /**
   * get a single time entry
   *
   * @param int $entry_id
   * @return array response content
   */
  public function getTimeEntry($entry_id) {
    if(!preg_match('!^\d+$!',$entry_id))
      throw new InvalidArgumentException("entry id must be a number.");
    return $this->processRequest("{$this->baseurl}time_entries/{$entry_id}.xml","GET");
  }

  /**
   * returns XML for existing time entry (for editing purposes)
   *
   * @param int $entry_id
   * @return mixed response content
   */
  public function editTimeEntry($entry_id) {
    if(!preg_match('!^\d+$!',$entry_id))
      throw new InvalidArgumentException("entry id must be a number.");
    return $this->processRequest("{$this->baseurl}time_entries/{$entry_id}/edit.xml","GET");
  }

  /**
   * edit a time tracking entry
   *
   * @param int $entry_id
   * @param int $person_id person time entry is for
   * @param string $date date in format YYYY-MM-DD
   * @param string $hours
   * @param string $description
   * @return array response content
   */
  public function updateTimeEntry(
    $entry_id,
    $person_id,
    $date,
    $hours,
    $description=null) {
    if(!preg_match('!^\d+$!',$entry_id))
      throw new InvalidArgumentException("entry id must be a number.");
    if(!preg_match('!^\d+$!',$person_id))
      throw new InvalidArgumentException("person id must be a number.");
    if(empty($date))
      throw new InvalidArgumentException("date cannot be empty.");
    if(empty($hours))
      throw new InvalidArgumentException("hours cannot be empty.");

    // if date is not in correct format, try to reformat it
    if(!preg_match('!^\d{4}-\d{2}-\d{2}$!',$date))
      $date = strftime('%Y-%m-%d',strtotime($date));

    $data = array(
              'time-entry'=>array(
                'person-id'=>$person_id,
                'date'=>$date,
                'hours'=>$hours,
                'description'=>$description
                )
            );

    $this->setupRequestBody($data);

    $response = $this->processRequest("{$this->baseurl}/time_entries/{$entry_id}.xml","PUT");

    return $response;

  }

  /**
   * deletes a time entry
   *
   * @param int $entry_id
   * @return array response content
   */
  public function deleteTimeEntry($entry_id) {
    if(!preg_match('!^\d+$!',$entry_id))
      throw new InvalidArgumentException("entry id must be a number.");
    return $this->processRequest("{$this->baseurl}time_entries/{$entry_id}.xml","DELETE");
  }

  /**
   * get a time entry report
   *
   * @param date $from  format YYYYMMDD or parsible by strtotime()
   * @param date $to  format YYYYMMDD or parsible by strtotime()
   * @param int subject_id person id to restrict time entries to
   * @param int to_item_id related task item id to restrict to
   * @param int filter_project_id project id to restrict to
   * @param int filter_company_id company id to restrict to
   * @return array response content
   */
  public function getTimeEntryReport(
    $from=null,
    $to=null,
    $subject_id=null,
    $to_item_id=null,
    $filter_project_id=null,
    $filter_company_id=null) {

    // if date not valid format, try to guess it
    if(isset($from)&&!preg_match('!^\d{8}$!',$from))
      $from = strftime('%Y%m%d',strtotime($from));
    if(isset($to)&&!preg_match('!^\d{8}$!',$to))
      $to = strftime('%Y%m%d',strtotime($to));

    return $this->processRequest(sprintf("{$this->baseurl}time_entries/report.xml?from=%s&to=%s&subject_id=%s&to_item_id=%s&filter_project_id=%s&filter_company_id=%s",
      $from,
      $to,
      (int)$subject_id,
      (int)$to_item_id,
      (int)$filter_project_id,
      (int)$filter_company_id
      ),"GET");
  }


}
