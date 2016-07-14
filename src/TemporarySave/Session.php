<?PHP

namespace WioForms\TemporarySave;

class Session extends AbstractTemporarySave
{
    public function saveFormData()
    {
        $toSave = [];
        foreach ($this->formStruct['Fields'] as $fieldName => $field) {
            $toSave[$fieldName] = $field['value'];
        }
        $_SESSION['_wioFormsTemporaryData'] = $toSave;
    }

    public function getFormData()
    {
        $savedData = false;

        if (isset($_SESSION['_wioFormsTemporaryData'])) {
            $savedData = $_SESSION['_wioFormsTemporaryData'];
        }

        return $savedData;
    }

    public function clearFormData()
    {
        if (isset($_SESSION['_wioFormsTemporaryData'])) {
            unset($_SESSION['_wioFormsTemporaryData']);
        }
    }
}
