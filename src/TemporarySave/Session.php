<?PHP

namespace WioForms\TemporarySave;

class Session extends AbstractTemporarySave
{

    /**
     * This method creates uid for a form entry.
     * Since in wio-forms there could be a form with same name presented
     * in different locations the uniq id takes the REQUEST_ID too.
     *
     * @return string
     */
    protected function getUid()
    {
        return base64_encode($this->formStruct['FormId'] . $_SERVER['REQUEST_URI']);
    }

    public function saveFormData()
    {
        $toSave = [];
        foreach ($this->formStruct['Fields'] as $fieldName => $field) {
            $toSave[$fieldName] = $field['value'];
        }
        $_SESSION['_wioFormsTemporaryData'][$this->getUid()] = $toSave;
    }

    public function getFormData()
    {
        if (!isset($_SESSION['_wioFormsTemporaryData'])
            || !is_array($_SESSION['_wioFormsTemporaryData'])) {

            $_SESSION['_wioFormsTemporaryData'] = [];
        }

        if (!isset($_SESSION['_wioFormsTemporaryData'][$this->getUid()])) {
            return false;
        }

        return $_SESSION['_wioFormsTemporaryData'][$this->getUid()];
    }

    public function clearFormData()
    {
        if (isset($_SESSION['_wioFormsTemporaryData'][$this->getUid()])) {
            unset($_SESSION['_wioFormsTemporaryData'][$this->getUid()]);
        }
    }
}
