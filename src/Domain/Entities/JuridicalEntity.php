<?php

namespace ZnKaz\Base\Domain\Entities;

class JuridicalEntity
{

    /**
     * @var int
     * 4 — для юридических лиц-резидентов;
     * 5 — для юридических лиц-нерезидентов;
     * 6 — для ИП(С);
     */
    private $type;
    
    /**
     * @var int
     * 0 — головного подразделения юридического лица или ИП(С);
     * 1 — филиала юридического лица или ИП(С);
     * 2 — представительства юридического лица или ИП(С);
     * 3 — крестьянское (фермерское) хозяйство, осуществляющее деятельность на основе совместного предпринимательства; 
     */
    private $part;
    
    private $registrationDate;

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getPart()
    {
        return $this->part;
    }

    public function setPart($part): void
    {
        $this->part = $part;
    }

    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate($registrationDate): void
    {
        $this->registrationDate = $registrationDate;
    }
}
