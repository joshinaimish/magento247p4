<?php
namespace Nxtech\Contact\Model;

use Nxtech\Contact\Api\ContactManagementInterface;
use Magento\Framework\DataObject;
use Nxtech\Contact\Api\Data\ContactInterface;



class ContactManagement implements ContactManagementInterface
{
    /**
     * {@inheritdoc}
     */
    public function submitForm($name, $email, $message)
    {

        /*  try {
             $this->resource->save($contact);
         } catch (\Exception $exception) {
             throw new CouldNotSaveException(
                 __(
                     'Could not save the contact: %1',
                     $exception->getMessage()
                 )
             );
         } */
        //return $name;

        // Process the form data (e.g., save to the database, send email, etc.)
        // Here we'll just return a success message for simplicity
        //$result = new DataObject();
        $response = [
            'success' => true,
            'message' => 'Form submitted successfully'
        ];
        return json_encode($response);
        //$result->setData($data);
        //return $result;
    }
}
