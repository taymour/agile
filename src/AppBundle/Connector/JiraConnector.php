<?php

namespace AppBundle\Connector;

use AppBundle\Entity\Issue;

class JiraConnector
{
    const REST_LATEST_ISSUE = '/rest/api/2/search';
    const REST_PROJECT = '/rest/api/2/project/';

    private $jiraApiDomainName;
    private $credentials;

    public function __construct($jiraApiDomainName = 'jira.atlassian.com', $credentials)
    {
        $this->jiraApiDomainName = $jiraApiDomainName;
        $this->credentials = $credentials;
    }

    public function getSprintIssues($sprintName)
    {
        $issuesList = $this->fetchApi(sprintf('%s', static::REST_LATEST_ISSUE), [
            'jql' => sprintf('status in ("Dev. à faire", "Dev. en cours", "Dev. terminé", "À tester", "À livrer", "En production", Terminé, Retours) AND Sprint = "%s"', $sprintName),
            'startAt'=> 0,
            'maxResults'=> 100,
            'fields'=> [
                'created',
                'issuetype',
                'status',
                'customfield_10004', // Story points
                'status',
            ]
        ]);
        $issues = [];

        foreach ($issuesList->issues as $issue) {
            $key = $issue->key;
            $issue = $issue->fields;

            if (!$issue->issuetype->subtask) {
                $issues[$key] = (new Issue())
                    ->setCreated(new \DateTime($issue->created))
                    ->setName($key)
                    ->setComplexity($issue->customfield_10004 ?: 0)
                    ->setCompleted(in_array($issue->status->statusCategory->key, ['done']))
                ;
            }
        }

        return $issues;
    }

    private function fetchApi($uri, $data = null)
    {
        $url = sprintf('https://%s%s', $this->jiraApiDomainName, $uri);
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-type: application/json',
            'Authorization: Basic ' . $this->credentials,
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        if ($data) {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($curl);

        $data = json_decode($response);

        return $data;
    }
}