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
            $savedData = get_object_vars(json_decode($_COOKIE['_wioFormsTemporaryData']));
        }

        return $savedData;
    }

    public function clearFormData()
    {
        setcookie('_wioFormsTemporaryData','{}', time()-3600*24*365, '/');
    }

}
?>
