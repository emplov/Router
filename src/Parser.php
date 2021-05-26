<?php

namespace Router;

// TODO change where option to equivalent name

class Parser
{
    protected function parseVariables($route)
    {
        $formatted_uri = $this->formatRegex($route->uri);
        $params_name = $this->findParams($route->uri);

        preg_match_all('#' . $formatted_uri . '#i', static::getInstance()->REQUEST_URI, $matches, PREG_PATTERN_ORDER);
        array_shift($matches);

        $params = [];

        foreach ($matches as $key => $match)
        {
            $name = str_replace(['{', '}'], '', $params_name[0][$key]);
            $params[$name] = $match[0];
        }

        return $params;
    }

    protected function matches($regex, $where = [])
    {
        $formatted_regex = $this->formatRegex($regex, $where);

        return (bool) preg_match('~^' . $formatted_regex . '$~', static::getInstance()->REQUEST_URI);
    }

    protected function findParams($regex)
    {
        preg_match_all('/\{[a-zA-Z\-\_]+\}/', $regex, $matches);
        return $matches;
    }

    protected function formatRegex($uri, $where = [])
    {
        $matches = $this->findParams($uri);

        if (!count($matches[0])) return $uri;

        foreach ($matches[0] as $key => $match)
        {
            $name = str_replace(['{', '}'], '', $match);
            if (isset($where[$name])) {
                $uri = preg_replace('#' . preg_quote($match) . '#i', '('.$where[$name].')', $uri);
            } else {
                $uri = preg_replace('#' . preg_quote($match) . '#i', '([a-zA-Z0-9]+)', $uri);
            }
        }

        return $uri;
    }
}
