<?php

namespace Ivoz\Provider\Domain\Model\Voicemail;

use Doctrine\Common\Collections\Selectable;
use Doctrine\Persistence\ObjectRepository;

interface VoicemailRepository extends ObjectRepository, Selectable
{

}
