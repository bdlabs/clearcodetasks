<?
/**
 *
 */
class SpellDamage
{
    private $spellpattern = array();
    private $spellsbuffor = array();
    private $damage;
    private $spellstartchars = "fe";
    private $spellendchars = "ai";

    public function __construct()
    {
        $this->spellpattern["fe"] = 1;
        $this->spellpattern["je"] = 2;
        $this->spellpattern["ne"] = 2;
        $this->spellpattern["ai"] = 2;
        $this->spellpattern["jee"] = 3;
        $this->spellpattern["ain"] = 3;
        $this->spellpattern["dai"] = 5;

    }

    public function getDamage($spell)
    {
        $this->spellsbuffor = array();
        $spell_ok = true;
        $start = strpos($spell, $this->spellstartchars, 0);
        $end = strrpos($spell, $this->spellendchars, 0);

        if ($start === false || $end === false) {
            $spell_ok = false;
        } else {
            $spell = substr($spell, $start, $end - $start + strlen($this->spellendchars));
            if (strpos($spell, "fe", 1) > 0) {
                $spell_ok = false;
            }

        }

        $this->damage = 0;
        if ($spell_ok) {
            $this->damage($spell);

            $damage = 0;
            foreach ($this->spellsbuffor as $key => $spellline) {
                $spellpatterns = array_slice(explode(",", $spellline), 1);
                $damagetmp = array_sum($spellpatterns);
                if ($damage < $damagetmp) {
                    $damage = $damagetmp;
                }

            }

            $this->damage = $damage;
        }

        return $this->damage;
    }

    private function damage($spell, $string = "")
    {
        $spelltmp = $spell;

        $spellsample1 = substr($spell, 0, 2);
        $spellsample2 = substr($spell, 0, 3);
        if (isset($this->spellpattern[$spellsample1])) {
            if (strlen(substr($spell, 2)) >= 2) {
                $this->damage(substr($spell, 2), $string . ',' . $this->spellpattern[$spellsample1]);
            } else {
                $this->spellsbuffor[] = $string . ',' . $this->spellpattern[$spellsample1];
                return true;
            }
        }

        if (isset($this->spellpattern[$spellsample2])) {
            if (strlen(substr($spell, 3)) >= 2) {
                $this->damage(substr($spell, 3), $string . ',' . $this->spellpattern[$spellsample2]);
            } else {
                $this->spellsbuffor[] = $string . ',' . $this->spellpattern[$spellsample2];
                return true;
            }

        }

        if (!isset($this->spellpattern[$spellsample1]) && !isset($spellpattern[$spellsample2])) {
            if (strlen($spell) > 0) {
                $this->damage(substr($spell, 1), $string . ',-1');
            }
        }
        return true;
    }
}

$damage = new SpellDamage();

echo 'Spell "xxxxxfejejeeaindaiyaiaixxxxxx" = ', $damage->getDamage("xxxxxfejejeeaindaiyaiaixxxxxx"), " damage.\n";
echo 'Spell "dadsafeokokok" = ', $damage->getDamage("dadsafeokokok"), " damage.\n";
echo 'Spell "feeai" = ', $damage->getDamage("feeai"), " damage.\n";
echo 'Spell "feaineain" = ', $damage->getDamage("feaineain"), " damage.\n";
echo 'Spell "jee" = ', $damage->getDamage("jee"), " damage.\n";
echo 'Spell "fefefefefeaiaiaiaiai" = ', $damage->getDamage("fefefefefeaiaiaiaiai"), " damage.\n";
echo 'Spell "fdafafeajain" = ', $damage->getDamage("fdafafeajain"), " damage.\n";
echo 'Spell "fexxxxxxxxxxai" = ', $damage->getDamage("fexxxxxxxxxxai"), " damage.\n";
