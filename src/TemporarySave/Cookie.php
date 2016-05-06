<?PHP
namespace WioForms\TemporarySave;

class Cookie extends AbstractTemporarySave
{

    public function saveFormData()
    {
        $toSave = [];
        foreach ($this->formStruct['Fields'] as $fieldName => $field)
        {
            $toSave[ $fieldName ] = $field['value'];
        }
        setcookie('_wioFormsTemporaryData', json_encode($toSave), time()+3600*24*365, '/' );
    }

    public function getFormData()
    {
        $savedData = false;

        if (isset($_COOKIE['_wioFormsTemporaryData']))
        {
            $decoded_cookie = json_decode($_COOKIE['_wioFormsTemporaryData']);
            if(is_object($decoded_cookie))
            {
                $savedData = get_object_vars($decoded_cookie);
            }
        }

        return $savedData;
    }

    public function clearFormData()
    {
        setcookie('_wioFormsTemporaryData','{}', time()-3600*24*365, '/');
    }

}
