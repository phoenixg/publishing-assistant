<?php

# Copyright 2010 Splunk, Inc.
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at 
# http://www.apache.org/licenses/LICENSE-2.0
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
#
#
# Email Service functions added by New Signature, copyright 2011

require_once 'SoapTypeTokenizer.php';

class EloquaServiceClientException extends Exception {}

class EloquaServiceClientLoginException extends EloquaServiceClientException {}
class EloquaServiceClientCreateException extends EloquaServiceClientException {}
class EloquaServiceClientRetrieveException extends EloquaServiceClientException {}
class EloquaServiceClientUpdateException extends EloquaServiceClientException {}
class EloquaServiceClientDeleteException extends EloquaServiceClientException {}
class EloquaServiceClientCreateAssetException extends EloquaServiceClientException {}
class EloquaServiceClientRetrieveAssetException extends EloquaServiceClientException {}
class EloquaServiceClientUpdateAssetException extends EloquaServiceClientException {}
class EloquaServiceClientDeleteAssetException extends EloquaServiceClientException {}
class EloquaServiceClientGetEmailActivitiesForRecipientsException extends EloquaServiceClientException {}

class EloquaServiceClient {
    protected $_instance = null;

    /**
     * Instantiate service, set SOAP header with credentials
     *
     * @param string $wsdl      Path to WSDL
     * @param string $username  Username
     * @param string $password  Password 
     */
    public function __construct($wsdl, $username, $password) { 
        $params = array('encoding' => 'utf-8',
                        'trace' => 1,
                        'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP
                        );

        $client = new SoapClient($wsdl, $params);
        $classmap = array();

        // at a high level, the following code takes a SOAP struct definition and 
        // translates it to valid PHP, which then gets eval()ed, so we can pass
        // the resulting classes as the classmap param to the SoapClient ctor,
        // which means we'll return the correct type of obj instead of stdClass.

        foreach($client->__getTypes() as $type) {
            $tokens = SoapTypeTokenizer::tokenize($type);
            $len = count($tokens);
            $properties['classes'] = array();
            $properties['other'] = array();

            if ($tokens[0]['token'] !== SOAP_TYPE_STRUCT)  continue; // ignore types that aren't structs

            for ($i = 0; $i < $len; ++$i) {
                $code = $tokens[$i]['code'];
                $token = $tokens[$i]['token'];
                if ($code === SOAP_NATIVE_TYPE) {
                    if ($token === SOAP_TYPE_STRUCT) {
                        $classCode = 'class ';
                        $i += 2; // skip whitespace token
                        $code = $tokens[$i]['code'];
                        $token = $tokens[$i]['token'];

                        // token is now the name of the struct
                        // add to classmap
                        $classmap[$token] = $token;

                        // if class exists (classes can be user-defined if add'l functionality is desired)
                        // we still want it in the classmap (above), but we don't want to re-declare it.
                        if (class_exists($token))  continue 2;

                        $classCode .= $token . ' {';
                        $i += 3; // skip whitespace, semicolon tokens
                    } else {
                        // some sort of SOAP type for a property, like dateTime
                        // we don't care about it, we just want the name
                        $i += 2; // skip whitespace token
                        $name = $tokens[$i]['token'];
                        $properties['other'][] = $name;
                    }
                } elseif ($code === SOAP_USER_TYPE) {
                   // user-defined class
                    $i += 2; // skip whitespace token
                    $name = $tokens[$i]['token'];
                    $properties['classes'][$name] = $token;
                }
            }

            // property definition section
            foreach ($properties['classes'] as $key => $class) {
                $classCode .= 'public $' . $key . ';';
            }
            foreach ($properties['other'] as $class) {
                $classCode .= 'public $' . $class . ';';
            }

            // create ctor
            $classCode .= 'public function __construct(';

            // populate ctor args
            foreach ($properties['other'] as $key => $class) {
                $classCode .= '$' . $class . ' = null,';
            }

            // remove extraneous trailing ',' if there
            if (substr($classCode, -1, 1) === ',')  $classCode = substr($classCode, 0, -1);
           
            // close args
            $classCode .= ')';

            // open ctor
            $classCode .= '{';

            // store ctor args as class properties
            foreach ($properties['other'] as $key => $class) {
                $classCode .= '$this->' . $class . ' = $' . $class . ';';
            }
            
            // instantiate member properties that are classes
            foreach ($properties['classes'] as $key => $class) {
                $classCode .= '$this->' . $key . ' = new ' . $class . '();';
            }

            // close ctor
            $classCode .= '}';

            // close class
            $classCode .= '}';

            eval($classCode);
        }

        // create another instance of the SoapClient, this time using the classmap
        $params['classmap'] = $classmap;
        $this->_instance = new SoapClient($wsdl, $params);

        // no way to do this without creating a SoapVar or patching __doRequest (by extending SoapClient)
        $authXML = <<<EOT
    <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
         <wsse:UsernameToken>
            <wsse:Username>$username</wsse:Username>
            <wsse:Password>$password</wsse:Password>
         </wsse:UsernameToken>
    </wsse:Security>
EOT;

        $authVar = new SoapVar($authXML, XSD_ANYXML);
        $authHeader = new SoapHeader('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd', 'Security', $authVar);

        $this->_instance->__setSoapHeaders($authHeader);
    }


    /***************************
     Utility methods
     **************************/


    /**
     * Retrieve last XML sent to SoapClient
     *
     * @return string  XML
     */
    public function getLastRequest() {
        return $this->_instance->__getLastRequest();
    }

    /**
     * Retrieve last XML retrieved from SoapClient
     *
     * @return string  XML
     */
    public function getLastResponse() {
        return $this->_instance->__getLastResponse();
    }


    /***************************
     Entity metadata operations
     **************************/


    /**
     * List entity types
     * 
     * @return ListEntityTypesResult  Entity types
     */
    public function ListEntityTypes() {
        return $this->_instance->ListEntityTypes();
    }

    /**
     * Describe a global entity type
     *
     * @param string $globalEntityType   Global entity type
     * 
     * @return DescribeEntityTypeResult  Entity types
     */
    public function DescribeEntityType($globalEntityType) {
        return $this->_instance->DescribeEntityType(array('globalEntityType' => $globalEntityType));
    }

    /**
     * Describe a specitic entity type
     *
     * @param EntityType $entityType  Entity type
     * 
     * @return DescribeEntityResult   Entity type metadata
     */
    public function DescribeEntity($entityType) {
        return $this->_instance->DescribeEntity(array('entityType' => $entityType));
    }


    /***************************
     Entity operations
     **************************/


    /**
     * Create an entity
     *
     * @param array $dynamicEntities  Array of DynamicEntity objects
     *
     * @return CreateResult           Result
     */
    public function Create($dynamicEntities) { 
        if (!is_array($dynamicEntities)) {
            throw new EloquaServiceClientCreateException('EloquaServiceClient::Create() must be passed an array of DynamicEntity objects.');
        }

        $result = $this->_instance->Create(array('entities' => $dynamicEntities));

        if ($result->CreateResult->CreateResult->ID === -1) 
            throw new EloquaServiceClientCreateException('EloquaServiceClient::Create() call failed to create entity.  Error, if any: ' . 
                                                         $result->CreateResult->CreateResult->Errors->Error->Message);

        return $result;
    }

    /**
     * Retrieve an entity
     *
     * @param EntityType $entityType  Entity type struct
     * @param array $ids              IDs to retrieve
     * @param array $fieldList        Field list (optional)
     *
     * @return RetrieveResult         Result
     */
    public function Retrieve($entityType, $ids, $fieldList = array()) { 
        if (!is_array($ids)) {
            throw new EloquaServiceClientRetrieveException('EloquaServiceClient::Retrieve() must be passed an array of ids.');
        }

        $result = $this->_instance->Retrieve(array('entityType' => $entityType, 'ids' => $ids, 'fieldList' => $fieldList));

        // don't try this at home, kids
        if (is_array($result->RetrieveResult->DynamicEntity)) {
            // 2+ results
            foreach ($result->RetrieveResult->DynamicEntity as $key => $dynamicEntity) {
                $result->RetrieveResult->DynamicEntity[$key] = clone $dynamicEntity;
            }
        } elseif ($result->RetrieveResult->DynamicEntity instanceof DynamicEntity) {
            // 1 result
            $result->RetrieveResult->DynamicEntity = array(clone $result->RetrieveResult->DynamicEntity);
        } else {
            // no results
            $result->RetrieveResult->DynamicEntity = array();
        }

        return new RetrieveResponseIterator($result);
    }

    /**
     * Update an entity
     *
     * @param array $dynamicEntities  Array of DynamicEntity objects
     *
     * @return UpdateResult           Result
     */
    public function Update($dynamicEntities) { 
        if (!is_array($dynamicEntities)) {
            throw new EloquaServiceClientUpdateException('EloquaServiceClient::Update() must be passed an array of DynamicEntity objects.');
        }

        return $this->_instance->Update(array('entities' => $dynamicEntities));
    }

    /**
     * Delete an entity
     *
     * @param EntityType $entityType  Entity type struct
     * @param array $ids              IDs to delete
     *
     * @return DeleteResult           Delete Result
     */
    public function Delete($entityType, $ids) { 
        if (!is_array($ids)) {
            throw new EloquaServiceClientDeleteException('EloquaServiceClient::Delete() must be passed an array of ids.');
        }

        return $this->_instance->Delete(array('entityType' => $entityType, 'ids' => $ids));
    }

    /**
     * Query Eloqua
     *
     * @param EntityType $eloquaType      Entity type struct (named eloquaType for some strange reason)
     * @param string $searchQuery         Query
     * @param array $fieldNames           Field names (optional)
     * @param int $pageNumber             Page number (optional)
     * @param int pageSize                Page size (optional)
     *
     * @return DynamicEntityQueryResults  Query results
     */
    public function Query($eloquaType, $searchQuery, $fieldNames = array(), $pageNumber = 1, $pageSize = 20) { 
        return $this->_instance->Query(array('eloquaType' => $eloquaType, 'searchQuery' => $searchQuery, 'fieldNames' => $fieldNames, 'pageNumber' => $pageNumber, 'pageSize' => $pageSize));
    }


    /***************************
     Asset metadata operations
     **************************/


    /**
     * List asset types
     * 
     * @return ListAssetTypeResult  Asset types
     */
    public function ListAssetTypes() {
        return $this->_instance->ListAssetTypes();
    }

    /**
     * Describe an asset type
     *
     * @param string $assetType         Asset type
     * 
     * @return DescribeAssetTypeResult  Description
     */
    public function DescribeAssetType($assetType) {
        return $this->_instance->DescribeAssetType(array('assetType' => $assetType));
    }

    /**
     * Describe a specitic asset
     *
     * @param AssetType $assetType  Asset type
     * 
     * @return DescribeAssetResult  Asset metadata
     */
    public function DescribeAsset($assetType) {
        return $this->_instance->DescribeAsset(array('assetType' => $assetType));
    }


    /***************************
     Asset operations
     **************************/


    /**
     * Create an asset
     *
     * @param array $assets       Array of DynamicAsset objects
     *
     * @return CreateAssetResult  Result
     */
    public function CreateAsset($assets) { 
        if (!is_array($assets)) {
            throw new EloquaServiceClientCreateAssetException('EloquaServiceClient::CreateAsset() must be passed an array of DynamicAsset objects.');
        }

        $result = $this->_instance->CreateAsset(array('assets' => $assets));

        if ($result->CreateAssetResult->CreateAssetResult->ID === -1) 
            throw new EloquaServiceClientCreateAssetException('EloquaServiceClient::CreateAsset() call failed to create entity.  Error, if any: ' . 
                                                              $result->CreateAssetResult->CreateAssetResult->Errors->Error->Message);
        return $result;
    }

    /**
     * Retrieve an asset
     *
     * @param AssetType $assetType  Asset type struct
     * @param array $ids            IDs to retrieve
     * @param array $fieldList      Field list (optional)
     *
     * @return RetrieveAssetResult  Result
     */
    public function RetrieveAsset($assetType, $ids, $fieldList = array()) { 
        if (!is_array($ids)) {
            throw new EloquaServiceClientRetrieveAssetException('EloquaServiceClient::RetrieveAsset() must be passed an array of ids.');
        }

        $result = $this->_instance->RetrieveAsset(array('assetType' => $assetType, 'ids' => $ids, 'fieldList' => $fieldList));

        // don't try this at home, kids
        if (is_array($result->RetrieveAssetResult->DynamicAsset)) {
            // 2+ results
            foreach ($result->RetrieveAssetResult->DynamicAsset as $key => $dynamicEntity) {
                $result->RetrieveAssetResult->DynamicAsset[$key] = clone $dynamicEntity;
            }
        } elseif ($result->RetrieveAssetResult->DynamicAsset instanceof DynamicAsset) {
            // 1 result
            $result->RetrieveAssetResult->DynamicAsset = array(clone $result->RetrieveAssetResult->DynamicAsset);
        } else {
            // no results
            $result->RetrieveAssetResult->DynamicAsset = array();
        }

        return new RetrieveAssetResponseIterator($result);
    }

    /**
     * Update an asset
     *
     * @param array $dynamicAssets  Array of DynamicAsset objects
     *
     * @return UpdateAssetResult    Result
     */
    public function UpdateAsset($dynamicAssets) { 
        if (!is_array($dynamicAssets)) {
            throw new EloquaServiceClientUpdateAssetException('EloquaServiceClient::UpdateAsset() must be passed an array of DynamicAsset objects.');
        }

        return $this->_instance->UpdateAsset(array('assets' => $dynamicAssets));
    }

    /**
     * Delete an asset
     *
     * @param AssetType $assetType  Asset type struct
     * @param array $ids            IDs to delete
     *
     * @return DeleteAssetResult    Delete Result
     */
    public function DeleteAsset($assetType, $ids) { 
        if (!is_array($ids)) {
            throw new EloquaServiceClientDeleteAssetException('EloquaServiceClient::DeleteAsset() must be passed an array of ids.');
        }

        return $this->_instance->DeleteAsset(array('assetType' => $assetType, 'ids' => $ids));
    }


    /***************************
     General API functions
     **************************/


    /**
     * List group membership
     *
     * @param DynamicEntity $entity         Dynamic Entity
     *
     * @return ListGroupMembershipResponse  Group membership
     */
     public function ListGroupMembership($entity) {
        return $this->_instance->ListGroupMembership(array('entity' => $entity));
     }

    /**
     * Add a member to a group
     *
     * @param DynamicEntity $entity  Dynamic Entity
     * @param DynamicAsset $asset    Dynamic Asset
     *
     * @return GroupMemberResult     Result
     */
     public function AddGroupMember($entity, $asset) {
        return $this->_instance->AddGroupMember(array('entity' => $entity, 'asset' => $asset));
     }

    /**
     * Remove a member from a group
     *
     * @param DynamicEntity $entity  Dynamic Entity
     * @param DynamicAsset $asset    Dynamic Asset
     *
     * @return GroupMemberResult     Result
     */
     public function RemoveGroupMember($entity, $asset) {
        return $this->_instance->RemoveGroupMember(array('entity' => $entity, 'asset' => $asset));
     }


    /***************************
     Undocumented API functions
     **************************/


    /**
     * List activity types
     *
     * @return ListActivityTypesResponse  Activity types
     */
    public function ListActivityTypes() {
        return $this->_instance->ListActivityTypes();

    }

    /**
     * Describe an activity type
     *
     * @param string $activityType           Activity type
     *
     * @return DescribeActivityTypeResponse  Activity type details
     */
    public function DescribeActivityType($activityType) {
        return $this->_instance->DescribeActivityType(array('activityType' => $activityType));
    }

    /**
     * Describe an activity
     * Who knows why the API takes ActivityType instead of activityType.
     *
     * @param string $ActivityType           Activity type
     *
     * @return DescribeActivityResponse      Activity details
     */
    public function DescribeActivity($activityType) {
        return $this->_instance->DescribeActivity(array('ActivityType' => $activityType));
    }

    /**
     * Get activities
     *
     * @param DynamicEntity $dynamicEntity  Entity
     * @param array $activityTypes          Activity types (e.g. 'WebVisit', 'FormSubmit')
     * @param string $startDate             Start date
     * @param string $endDate               End date
     *
     * @return GetActivitiesResponse        Activities
     */
    public function GetActivities($dynamicEntity, $activityTypes, $startDate, $endDate) {
        return $this->_instance->GetActivities(array('dynamicEntity' => $dynamicEntity,
                                                     'activityTypes' => $activityTypes,
                                                     'startDate' => $startDate,
                                                     'endDate' => $endDate));
    }

    /**
     * Get email activities for recipients
     *
     * @param array $emailAddresses                     Email addresses
     * @param array $emailIDs                           Email IDs (IDs of the emails themselves)
     * @param string $pageSize                          Page size
     * @param string $requestedPage                     Page number
     *
     * @return GetEmailActivitiesForRecipientsResponse  Activities
     */
    public function GetEmailActivitiesForRecipients($emailAddresses, $emailIDs, $pageSize = 20, $requestedPage = 1) {
        if (!is_array($emailAddresses)) {
            throw new EloquaServiceClientGetEmailActivitiesForRecipientsException('EloquaServiceClient::GetEmailActivitiesForRecipients() must be passed an array of email addresses.');
        }

        if (!is_array($emailIDs)) {
            throw new EloquaServiceClientGetEmailActivitiesForRecipientsException('EloquaServiceClient::GetEmailActivitiesForRecipients() must be passed an array of email IDs.');
        }

        return $this->_instance->GetEmailActivitiesForRecipients(array('emailAddresses' => $emailAddresses, 
                                                                       'emailIDs' => $emailIDs, 
                                                                       'pageSize' => $pageSize, 
                                                                       'requestedPage' => $requestedPage));
    }

    /**
     * Send a quick email to an entity
     *
     * @param DynamicAsset $asset    Dynamic Asset
     * @param DynamicEntity $entity  Dynamic Entity
     * @param array $options         Options, possible keys are AllowResend, SendToBouncebacked, SendToEmailGroupUnsubscribed, SendToMasterExcluded, SendToUnsubscribed
     *                               Values are booleans
     *
     * @return SendQuickEmailResponse  Result
     */
     public function SendQuickEmail($asset, $entity, $options = array()) {
        return $this->_instance->SendQuickEmail(array('asset' => $asset, 'entity' => $entity, 'options' => $options));
     }

    /**
     * Get status of a quick email send
     *
     * @param int $deploymentId             Deployment ID from SendQuickEmail() call
     *
     * @return GetQuickEmailStatusResponse  Result
     */
     public function GetQuickEmailStatus($deploymentId) {
        return $this->_instance->GetQuickEmailStatus(array('deploymentId' => $deploymentId));
     }

     /*****************************
      * Email Service functions
      *****************************/

     /**
      * Get list of email assets and associated IDs
      *
      * @return ListEmails   Result 
      */
     public function ListEmails() {
       return $this->_instance->ListEmails();
     }

     /**
      * Search through email names that meet a specified string match
      *
      * @param string $emailName
      * @return SearchEmail   Result
      */
     public function SearchEmail($emailName) {
       return $this->_instance->SearchEmail(array('emailName'=>$emailName));
     }

     /**
      *
      * @param int $emailID
      * @return GetEmailDetails   Result
      */
     public function GetEmailDetails($emailId) {
        return $this->_instance->GetEmailDetails(array('emailId'=>$emailId));
     }

     /**
      * Creates a new HTML Email in Eloqua
      *
      * @param EmailDetails $emailDetails
      * @return CreateHTMLEmail   Result
      */
     public function CreateHtmlEmail($emailDetails) {
      return $this->_instance->CreateHtmlEmail(array('email'=>$emailDetails));
     }

     /**
      * Lists Email Groups in Eloqua
      *
      * @return EmailGroup
      */
     public function ListEmailGroups() {
       return $this->_instance->ListEmailGroups();
     }

     /*
      * Lists Email Headers in Eloqua
      */
     public function ListEmailHeaders() {
       return $this->_instance->ListEmailHeaders();
     }

     /*
      * Lists Email Footers in Eloqua
      */
     public function ListEmailFooters() {
       return $this->_instance->ListEmailFooters();
     }

     /*
      * List Email Distribution Lists in Eloqua
      */
     public function ListDistributionLists() {
       return $this->_instance->ListDistributionLists();
     }

     /**
      * Shows the current status of a specified deployment
      *
      * @param int $deploymentId
      * @return Deployment
      */
     public function GetDeployment($deploymentId) {
       return $this->_instance->GetDeployment(array('deploymentId' => $deploymentId));
     }

     /**
      * Create an email deployment in Eloqua
      *
      * @param DeploymentSettings $deploySettings
      * @return Deployment
      */
     public function Deploy($deploySettings) {
       return $this->_instance->Deploy(array('deploySettings'=>$deploySettings));
     }
     
     /**
      * List all redirect links in Eloqua
      */
     public function ListRedirectLinks() {
       return $this->_instance->ListRedirectLinks();
     }
     
     /**
      * Search existing redirect links
      * @param string $url
      * @return array 
      */
     public function SearchRedirectLinks($url) {
       return $this->_instance->SearchRedirectLinks(array('url' => $url));
     }
     
     /**
      * Create a new redirect link
      * @param string $url
      * @return RedirectLink 
      */
     public function CreateRedirectLink($url) {
       return $this->_instance->CreateRedirectLink(array('url' => $url));
     }

}

// set up some sane classes with some helper methods so it's not so arduous to work with the API
// any class defined in the WSDL, but not defined below, will be auto-generated by the SOAP client

// the __clone method here is to work around an issue where SoapClient returns types in the classmap
// property that its ctor takes as objects of the correct type, but instances where __construct,
// __get, and __set don't work.  __clone, however, does, as do userland methods.
// the solution is to return a clone of the object, and transfer the properties (two of the three
// are just references to objects, so this is quite cheap).

class DynamicAsset {
    public $AssetType;
    public $FieldValueCollection;
    public $Id;

    public function __construct($assetType = null) {
         $this->AssetType = $assetType;
         $this->FieldValueCollection = new DynamicAssetFields();
         $this->FieldValueCollection->AssetFields = array();
    }

    public function __clone() {
        $obj = new DynamicAsset($this->AssetType->Name, $this->AssetType->Type);
        $obj->FieldValueCollection = $this->FieldValueCollection;
        $obj->Id = $this->Id;
        return $obj;
    }

    public function __get($key) {
        // ease the ID vs. Id pain that is rampant in the Eloqua API
        if ($key === 'ID') {
            return $this->Id;
        }

        foreach ($this->FieldValueCollection->AssetFields as $arr) {
            if ($arr->InternalName === $key)  return $arr->Value;
        }
    }
    
    public function __set($key, $val) {
        // ease the ID vs. Id pain that is rampant in the Eloqua API
        if ($key === 'ID') {
            $this->Id = $val;
            return;
        }

        foreach ($this->FieldValueCollection->AssetFields as $idx => $field) {
            if ($field->InternalName === $key) {
                $this->FieldValueCollection->AssetFields[$idx]->Value = $val;
                return;
            }
        }

        // this must be an object, not an array, so it's the same structure the SOAP client returns from a 
        // retrieval call (such as Retrieve())
        $obj = new stdClass();
        $obj->InternalName = $key;
        $obj->Value = $val;
        $this->FieldValueCollection->AssetFields[] = $obj;
    }

    public function __unset($key) {
        foreach ($this->FieldValueCollection->AssetFields as $idx => $arr) {
            if ($arr->InternalName === $key)  unset($this->FieldValueCollection->AssetFields[$idx]);
        }
    }
}

class DynamicEntity {
    public $EntityType;
    public $FieldValueCollection;
    public $Id;

    public function __construct($name, $type = 'Base') {
         $this->EntityType = new EntityType(0, $name, $type);
         $this->FieldValueCollection = new DynamicEntityFields();
         $this->FieldValueCollection->EntityFields = array();
    }

    public function __clone() {
        $obj = new DynamicEntity($this->EntityType->Name, $this->EntityType->Type);
        $obj->FieldValueCollection = $this->FieldValueCollection;
        $obj->Id = $this->Id;
        return $obj;
    }

    public function __get($key) {
        // ease the ID vs. Id pain that is rampant in the Eloqua API
        if ($key === 'ID') {
            return $this->Id;
        }

        foreach ($this->FieldValueCollection->EntityFields as $arr) {
            if ($arr->InternalName === $key)  return $arr->Value;
        }
    }
    
    public function __set($key, $val) {
        // ease the ID vs. Id pain that is rampant in the Eloqua API
        if ($key === 'ID') {
            $this->Id = $val;
            return;
        }

        foreach ($this->FieldValueCollection->EntityFields as $idx => $field) {
            if ($field->InternalName === $key) {
                $this->FieldValueCollection->EntityFields[$idx]->Value = $val;
                return;
            }
        }

        // this must be an object, not an array, so it's the same structure the SOAP client returns from a 
        // retrieval call (such as Retrieve())
        $obj = new stdClass();
        $obj->InternalName = $key;
        $obj->Value = $val;
        $this->FieldValueCollection->EntityFields[] = $obj;
    }

    public function __unset($key) {
        foreach ($this->FieldValueCollection->EntityFields as $idx => $arr) {
            if ($arr->InternalName === $key) {
                unset($this->FieldValueCollection->EntityFields[$idx]);
                return;
            }
        }
    }
}

class EmailDetails {
  public $HtmlContent;
  public $GroupId;
  public $Subject;
  public $Name;
  public $HeaderId;
  public $FooterId;
  public $EncodingId;
  public $FromName;
  public $FromAddress;
  public $ReplyToAddress;
  public $ReplyToName;
  public $BouncebackAddress;
  public $SendHtml;
  public $Tracked;
  public $AutoGenerateTextContent;
  public $NeverExpires;
  public $SendToUnsubscribes;

  public function  __construct($name = null, $subject = null, $group = null, $html = null, $from = null, $options = array()) {
    $this->HtmlContent = $html;
    $this->GroupId = $group;
    $this->Subject = $subject;
    $this->Name = $name;
    $this->FromName = $from;
    $this->HeaderId = !empty($options['HeaderId']) ? $options['HeaderId'] : 8;
    $this->FooterId = !empty($options['FooterId']) ? $options['FooterId'] : 22;
    $this->EncodingId = !empty($options['EncodingId']) ? $options['EncodingId'] : 3;
    $this->FromAddress = !empty($options['FromAddress']) ? $options['FromAddress'] : 'alerts@aaas-science.org';
    $this->ReplyToAddress = !empty($options['ReplyToAddress']) ? $options['ReplyToAddress'] : $this->FromAddress;
    $this->ReplyToName = !empty($options['ReplyToName']) ? $options['ReplyToName'] : $this->FromName;
    $this->BouncebackAddress = !empty($options['BouncebackAddress']) ? $options['AutoGenerateTextContent'] : 'info@aaas-science.org';
    $this->SendHtml = !empty($options['SendHtml']) ? $options['SendHtml'] : true;
    $this->Tracked = !empty($options['Tracked']) ? $options['Tracked'] : true;
    $this->AutoGenerateTextContent = !empty($options['AutoGenerateTextContent']) ? $options['AutoGenerateTextContent'] : true;
    $this->NeverExpires = !empty($options['NeverExpires']) ? $options['NeverExpires'] : false;
    $this->SendToUnsubscribes = !empty($options['SendToUnsubscribes']) ? $options['SendToUnsubscribes'] : false;
  }

}

class DeploymentSettings {
  public $Name;
  public $EmailId;
  public $SendOnBehalfOfUser;
  public $AllowResend;
  public $AllowSendToBouncebacked;
  public $AllowSendToEmailGroupUnsubscribed;
  public $AllowSendToMasterExcludeList;
  public $AllowSendToUnsubscribed;
  public $NotificationEmailAddress;
  public $DeploymentDate;

  public function __construct($options = array()) {
    $this->Name = $options['Name'];
    $this->EmailId = $options['EmailId'];
    $date = !empty($options['DeploymentDate']) ? $options['DeploymentDate'] : time();
    $this->DeploymentDate = date('Y-m-d\TH:i:s.00', $date);
    //$this->SendOnBehalfOfUser = !empty($options['SendOnBehalfOfUser']) ? $options['SendOnBehalfOfUser'] : 'API.USER';
    $this->SendOnBehalfOfUser = !empty($options['SendOnBehalfOfUser']) ? $options['SendOnBehalfOfUser'] : null;
    $this->AllowResend = !empty($options['AllowResend']) ? $options['AllowResend'] : true;
    $this->AllowSendToBouncebacked = !empty($options['AllowSendToBouncebacked']) ? $options['AllowSendToBouncebacked'] : false;
    $this->AllowSendToEmailGroupUnsubscribed = !empty($options['AllowSendToEmailGroupUnsubscribed']) ? $options['AllowSendToEmailGroupUnsubscribed'] : false;
    $this->AllowSendToMasterExcludeList = !empty($options['AllowSendToMasterExcludeList']) ? $options['AllowSendToMasterExcludeList'] : false;
    $this->AllowSendToUnsubscribed = !empty($options['AllowSendToUnsubscribed']) ? $options['AllowSendToUnsubscribed'] : false;
    $this->NotificationEmailAddress = !empty($options['NotificationEmailAddress']) ? $options['NotificationEmailAddress'] : 'scialerts@aaas.org';

    if(!empty($options['DistributionListId'])) {
      $this->SignatureRuleId = 0;
      $this->DistributionListId = $options['DistributionListId'];
    } else if (!empty($options['ContactId'])) {
      $this->ContactId = $options['ContactId'];
    } else {
      die('Missing ID of distibution list or contact');
    }

  }
}

class RedirectLink {
  public $Name;
  public $Tag;
  
  public function __construct($options = array()) {
    $this->Name = $options['Name'];
    $this->Tag = $options['Tag'];
  }
}

// Base class for creating iterators over nested arrays in response objects
abstract class ResponseIterator implements Iterator, Countable, ArrayAccess {
    protected $_cursor;
    protected $_response;
    protected $_items;

    abstract public function __construct();

    public function __get($key) {
        return $this->_response->$key;
    }

    /* Countable method */

    public function count() {
        return count($this->_items);
    }

    /* Iterator methods */

    /**
     * Return value at cursor
     *
     * @return string  Array value
     */
    public function current() {
        return $this->_items[$this->_cursor];
    }

    /**
     * Return key at cursor
     *
     * @return int  Key
     */
    public function key() {
        return (isset($this->_items[$this->_cursor])) ? $this->_cursor : null;
    }

    /**
     * Advance array cursor
     */
    public function next() {
        ++$this->_cursor;
    }

    /**
     * Rewind iterator cursor
     */
    public function rewind() {
        $this->_cursor = 0;
    }

    /**
     * Return whether we have a valid array cursor position
     */
    public function valid() {
        return $this->key() !== null;
    }

    /* ArrayAccess methods */

    public function offsetGet($key) {
        return $this->_items[$key];
    }
    
    public function offsetSet($key, $val) {
        $this->_items[$key] = $val;
    }

    public function offsetUnset($key) {
        unset($this->_items[$key]);
    }

    public function offsetExists($key) {
        return isset($this->_items[$key]);
    }
}

class RetrieveResponseIterator extends ResponseIterator {
    /**
     * ctor
     * 
     * @param RetrieveResponse $response  RetrieveResponse object holding an array of DynamicEntity objects
     */ 
    public function __construct($response) {
        $this->_response = $response;
        $this->_items = &$this->_response->RetrieveResult->DynamicEntity;
    }
}

class RetrieveAssetResponseIterator extends ResponseIterator {
    /**
     * ctor
     * 
     * @param RetrieveAssetResponse $response  RetrieveAssetResponse object holding an array of DynamicAsset objects
     */ 
    public function __construct($response) {
        $this->_response = $response;
        $this->_items = &$this->_response->RetrieveAssetResult->DynamicAsset;
    }
}

?>
