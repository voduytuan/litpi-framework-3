

    public static function get{{PROPERTYCONSTANTUCFIRST}}List()
    {
        $output = array();

        {{PROPERTYCONSTANT_LIST}}

        return $output;
    }

    public function get{{PROPERTYCONSTANTUCFIRST}}Name()
    {
        $name = '';

        switch ($this->{{PROPERTYCONSTANT}}) {
            {{PROPERTYCONSTANT_GETNAME}}
        }

        return $name;
    }

    public function check{{PROPERTYCONSTANTUCFIRST}}Name($name)
    {
        $name = strtolower($name);

        if ({{PROPERTYCONSTANT_CHECK}}) {
            return true;
        } else {
            return false;
        }
    }