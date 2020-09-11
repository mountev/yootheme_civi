<?php

class SourceController
{
    /**
     * @param Request  $request
     * @param Response $response
     *
     * @throws \Exception
     *
     * @return Response
     */
    public static function events(Request $request, Response $response)
    {
        $events = [];

        require_once JPATH_ROOT . '/components/com_civicrm/civicrm.php';
        $result = civicrm_api3('Event', 'get', [
          'sequential' => 1,
        ]);
        if (!empty($result['values'])) {
          $events = $result['values'];
        }


        CRM_Core_Error::debug_var('$events', $events);
        $ret = $response->withJson((object) $events);
        CRM_Core_Error::debug_var('$ret', $ret);
        return $ret;
    }
}

