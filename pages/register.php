<?php
if (!defined('INITIALIZED'))
	exit;

$default = 'all';

/*
$voc = array(); // if empty, Rook Sample will be used
$voc[1] = 'Sorcerer';
$voc[2] = 'Druid';
$voc[3] = 'Paladin';
$voc[4] = 'Knight';
*/

$suggestname = NULL; // not available
$version = '1078'; // for download link

if (isset($_POST['step']) && $_POST['step'] == 'docreate') {
	$e = array();
	$s = isset($_POST['accountName']) ? $_POST['accountName'] : '';
	if ($s == '')
		$e['acc'] = 'Please enter an account name!';
	elseif (strlen($s) < 4)
		$e['acc'] = 'This account name is too short!';
	elseif (strlen($s) > 30)
		$e['acc'] = 'This account name is too long!';
	else {
		$s = strtoupper($s);

		if (!ctype_alnum($s))
			$e['acc'] = 'This account name has an invalid format. Your account name may only consist of numbers 0-9 and letters A-Z!';
		elseif (!preg_match('/[A-Z0-9]/', $s))
			$e['acc'] = 'Your account name must include at least one letter A-Z!';
		else {
			$acc = new Account($s, Account::LOADTYPE_NAME);
			if ($acc->isLoaded())
				$e['acc'] = 'This account name is already used. Please select another one!';
		}
	}
/*
	$s = isset($_POST['characterName']) ? trim($_POST['characterName']) : '';

	if (empty($s))
		$e['name'] = 'Please enter a name for your character!';
	elseif (strlen($s) < 2 || strlen($s) > 30)
		$e['name'] = 'A name must have at least 2 but no more than 30 letters!';
	elseif (preg_match('/[^a-zA-Z ]/', $s))
		$e['name'] = 'This name contains invalid letters. Please use only A-Z, a-z and space!';
	elseif (!ctype_upper($s[0]))
		$e['name'] = 'The first letter of a name has to be a capital letter!';
	elseif (strpos($s, '  ') !== false)
		$e['name'] = 'This name contains more than one space between words. Please use only one space between words!';
	else {
		foreach (explode(' ', $s) as $k =>$v) {
			$words[$k] = str_split($v);
			$len = strlen($v);
			if ($len == 1) {
				$e['name'] = 'This name contains a word with only one letter. Please use more than one letter for each word!';
				break;
			}
			elseif ($len > 14) {
				$e['name'] = 'This name contains a word that is too long. Please use no more than 14 letters for each word!';
				break;
			}
		}
		if (!isset($e['name'])) {
			$total=0;
			foreach ($words as $k =>$p) {
				if (isset($e['name']))
					break;
				$total++;
				if ($total > 3) {
					$e['name'] = 'This name contains more than 3 words. Please choose another name!';
					break;
				}
				$len=0;
				foreach ($p as $i =>$j) {
					$len++;
					if ($i != 0 && ctype_upper($j)) {
						$e['name'] = 'In names capital letters are only allowed at the beginning of a word!';
						break;
					}
					elseif ($i == $len-1) {
						$ff=null;
						for($h=0;$h<strlen($v); $h++) {
							if (in_array(strtolower($v[$h]), array('a','e','i','o','u')) !== false) {
								$ff=true;
								break;
							}
						}
						if (!$ff) {
							$e['name'] = 'This name contains a word without vowels. Please choose another name!';
							break;
						}
					}
				}
			}
			if (!isset($e['name'])) {
				$s = strtolower($s);
				for($i = 0; $i < strlen($s); $i++)
					if ($s[$i] == $s[($i+1)] && $s[$i] == $s[($i+2)]) {
						$e['name'] = 'This character name is already used. Please select another one!';
						break;
					}
				if (!isset($e['name'])) {
					foreach (array('puta', 'caralho', 'karalho', 'porra', 'buceta', 'simone', 'corno','training', 'devovorga', 'energized raging mage', 'ot', 'otserv', 'thunder', 'serve', 'trimera', 'drugo', 'drugovich','adm','godsao','godzao','godsinho','GoDzinho','godzinho','Godzinho','god','GoD','GOd','God','GOD','mage mad', 'mad mage', 'abyssador', 'armadile', 'cliff strider', 'crystalcrusher', 'damaged crystal golem', 'deathstrike', 'stone devourer', 'hideous fungus', 'humongous fungus', 'humorless fungus', 'enraged crystal golem', 'wiggler', 'lost berserker', 'lava golem', 'magma crawler', 'weeper', 'gnomevil', 'ironblight', 'orewalker', 'vulcongra', 'mushroom sniffer', 'parasite', 'infected weeper', 'doctor gnomedix', 'pythius the rotten', 'lady bug', 'manta ray', 'deepling worker', 'god alone', 'bazir', 'crawler', 'deepling guard', 'deepling spellsinger', 'deepling warrior', 'manta', 'shark', 'spidris', 'spitter', 'waspoid', 'kollos', 'insectoid scout', 'calamary', 'fish', 'hive overseer', 'jellyfish', 'northern pike', 'spidris elite', 'swarmer', 'insectoid worker', 'deepling brawler', 'deepling master librarian', 'deepling tyrant', 'deepling elite', 'floor blob', 'hive pore', 'lesser swarmer', 'adi', 'adinmin', 'adimi', 'adimin', 'task', 'task clous', 'queen', 'queen eloise', " ' ", 'gm','cm', 'aff ', 'god ', 'abc', 'tutor', 'game', 'admin', 'the', 'rashid', 'alesar', 'benjamin', 'suzy', 'tajis', 'sarina', 'poach', 'Nah Bob', 'galuna', 'elane', 'chonndur', 'chondur', 'addoner', 'haroun', 'yaman', 'towncryer', 'jaul', 'obujos', 'hive overseer', 'crawler', 'deepling guard', 'deepling spellsinger', 'deepling warrior', 'lady bug', 'manta', 'shark', 'spidris', 'spitter', 'waspoid', 'kollos', 'lion event', 'tiger event', 'gerador azul i', 'gerador azul ii', 'gerador azul iii', 'gerador vermelho i', 'gerador vermelho ii', 'gerador vermelho iii', 'zombie event', 'emperium', 'guard', 'feromous', 'abomination fury', 'alvo', 'gate', 'elder beholder', 'trainer', 'beholder', 'yalaharian', 'protect cube', 'protect castle', 'protect statue', 'castle gates', 'lizard magistratus', 'lizard noble', 'askarak demon', 'askarak lord', 'askarak prince', 'bog frog', 'clay guardian', 'crystal wolf', 'death priest', 'deepling scout', 'desperate white deer', 'diamond servant', 'dromedary', 'donkey', 'elder mummy', 'enraged white deer', 'feverish citizen', 'filth toad', 'firestarter', 'ghoulish hyaena', 'golden servant', 'grave guard', 'groam', 'honour guard', 'horestis', 'horse', 'incredible old witch', 'insectoid scout', 'iron servant', 'kraknaknork', 'kraknaknork demon', 'running elite orc guard', 'sacred spider', 'sandstone scorpion', 'shaburak demon', 'shaburak lord', 'shaburak prince', 'slug', 'spider queen', 'starving wolf', 'thornfire wolf', 'tomb servant', 'weakened demon', 'wild dog', 'white deer', 'yielothax', 'boar', 'cake golem', 'crustacea gigantica', 'draptor', 'ghost rat', 'midnight panther', 'slime puddle', 'spectral scum', 'stampor', 'undead cavebear', 'brimstone bug', 'draken abomination', 'draken elite', 'fury of the emperor', 'glitterscale', 'heoni', 'lizard abomination', 'snake god essence', 'scorn of the emperor', 'snake think', 'souleater', 'spite of the emperor', 'wrath of the emperor', 'draken spellweaver', 'draken warmaster', 'ghastly dragon', 'gnarlhound', 'insect swarm', 'killer caiman', 'lancer beetle', 'lizard chosen', 'lizard dragon priest', 'lizard high guard', 'lizard legionnaire', 'lizard zaogun', 'orc marauder', 'sandcrawler', 'terramite', 'undead prospector', 'skeleton', 'wailing widow', 'berserker chicken', 'boogey', 'bride of night', 'demon parrot', 'dirtbeard', 'deer', 'esmeralda', 'essence of darkness', 'evil mastermind', 'evil sheep lord', 'evil sheep', 'fahim the wise', 'hide', 'hot dog', 'killer rabbit', 'medusa', 'mephiles', 'merikh the slaughterer', 'monstor', 'rottie the rotworm', 'shardhead', 'the bloodtusk', 'the many', 'the noxious spawn', 'the snapper', 'badger', 'bat', 'bear', 'black sheep', 'blood crab', 'blood crab underwater', 'carrion worm', 'cat', 'cave rat', 'chicken', 'cockroach', 'crab', 'crocodile', 'dire penguin', 'dog', 'elephant', 'flamingo', 'hyaena', 'husky', 'kitty', 'lion', 'mad sheep', 'mammoth', 'panda', 'parrot', 'penguin', 'pig', 'polar bear', 'rabbit', 'rat', 'rotworm', 'seagull', 'sheep', 'silver rabbit', 'skunk', 'squirrel', 'terror bird', 'thornback tortoise', 'tiger', 'tortoise', 'tortoise anti-bot', 'war wolf', 'winter wolf', 'wolf', 'dwarf', 'dwarf guard', 'dwarf geomancer', 'dwarf soldier', 'quara pincher', 'quara predator', 'quara constrictor', 'quara hydromancer', 'quara mantassin', 'quara pincher scout', 'quara predator scout', 'quara constrictor scout', 'quara hydromancer scout', 'quara mantassin scout', 'sea serpent', 'young sea serpent', 'achad', 'axeitus headbanger', 'bloodpaw', 'bovinus', 'colerian the barbarian', 'cursed gladiator', 'frostfur', 'orcus the cruel', 'rocky', 'the hairy one', 'avalanche', 'drasilla', 'grimgor guteater', 'kreebosh the exile', 'slim', 'spirit of earth', 'spirit of fire', 'spirit of water', 'the dark dancer', 'the hag', 'darakan the executioner', 'deathbringer', 'fallen mooh tah master ghar', 'gnorre chyllson', 'norgle glacierbeard', 'svoren the mad', 'the masked marauder', 'the obliverator', 'the pit lord', 'webster', 'flamethrower', 'hell hole', 'lavahole', 'dwarf dispenser', 'magicthrower', 'magic pillar', 'pillar', 'mechanical figher', 'plaguethrower', 'poisonthrower', 'shredderthrower', 'barbarian bloodwalker', 'barbarian brutetamer', 'barbarian headsplitter', 'barbarian skullhunter', 'arachir the ancient one', 'armenius', 'arthei', 'apprentice sheng', 'azerus', 'barbaria', 'battlemaster zunzu', 'big boss trolliver', 'blistering fire elemental', 'boreth', 'captain jones', 'chizzoron the distorter', 'the countess sorrow', 'cublarc the plunderer', 'deadeye devious', 'demodras', 'dharalion', 'diblis the fair', 'diseased bill', 'diseased dan', 'diseased fred', 'dracola', 'dreadmaw', 'earth overlord', 'energy overlord', 'fernfang', 'ferumbras', 'fire overlord', 'fleabringer', 'foreman kneebiter', 'freegoiz', 'general murius', 'grandfather tridian', 'grand mother foulscale', 'grorlam', 'hairman the huge', 'lizard templar', 'ice overlord', 'inky', 'koshei the deathless', 'lersatio', 'lethal lissy', 'lizard gate guardian', 'lord of the elements', 'mad technomancer', 'man in the cave', 'marziel', 'massacre', 'mooh tah master', 'mr. punish', 'munster', 'necropharus', 'ron the ripper', 'renegade orc', 'rotworm queen', 'rukor zad', 'shadow of boreth', 'shadow of lersatio', 'shadow of marziel', 'shard of corruption', 'sharptooth', 'sir valorcrest', 'splasher', 'smuggler baron silvertoe', 'stonecracker', 'the blightfather', 'the big bad one', 'the collector', 'the count', 'the evil eye', 'the frog prince', 'the handmaiden', 'the horned fox', 'the imperor', 'the old widow', 'the plasmother', 'the voice of ruin', 'the weakened count', 'thul', 'tiquandas revenge', 'ungreez', 'warlord ruzad', 'yakchal', 'yalahari', 'yeti', 'zarabustor', 'zebelon duskbringer', 'zulazza the corruptor', 'chakoya toolshaper', 'chakoya tribewarden', 'chakoya windcaller', 'dark torturer', 'demon', 'destroyer', 'diabolic imp', 'fire devil', 'gozzler', 'hand of cursed fate', 'hellfire fighter', 'hellhound', 'hellspawn', 'juggernaut', 'nightmare', 'nightmare scion', 'nightstalker', 'plaguesmith', 'bazir', 'orshabaal', 'zoralurk', 'frost dragon', 'wyrm', 'dragon lord', 'dragon', 'hydra', 'dragon hatchling', 'dragon lord hatchling', 'frost dragon hatchling', 'undead dragon', 'wyvern', 'blazing fire elemental', 'charged energy elemental', 'earth elemental', 'energy elemental', 'fire elemental', 'jagged earth elemental', 'massive earth elemental', 'massive energy elemental', 'massive fire elemental', 'massive water elemental', 'muddy earth elemental', 'overcharged energy elemental', 'roaring water elemental', 'slick water elemental', 'water elemental', 'elf arcanist', 'elf scout', 'elf', 'bones', 'deathslicer', 'exp bug', 'eye of the seven', 'fluffy', 'gamemaster', 'goblin demon', 'grynch clan goblin', 'hacker', 'the halloween hare', 'the ruthless herald', 'minishabaal', 'primitive', 'servant golem', 'tha exp carrier', 'the mutated pumpkin', 'tibia bug', 'undead minion', 'ashmunrah', 'arkhothep', 'dipthrah', 'mahrdis', 'morguthis', 'omruc', 'rahemos', 'thalas', 'vashresamun', 'frost giant', 'frost giantess', 'cyclops smith', 'cyclops drone', 'behemoth', 'cyclops', 'ice golem', 'the old whopper', 'stone golem', 'war golem', 'worker golem', 'damaged worker golem', 'goblin leader', 'goblin scavenger', 'goblin', 'goblin assassin', 'dworc fleshhunter', 'dworc venomsniper', 'dworc voodoomaster', 'acolyte of the cult', 'adept of the cult', 'amazon', 'crazed beggar', 'enlightened of the cult', 'novice of the cult', 'dark monk', 'monk', 'dark apprentice', 'dark magician', 'fury', 'gladiator', 'gang member', 'ice witch', 'infernalist', 'mad scientist', 'warlock', 'witch', 'necromancer', 'priestess', 'assasin', 'bandit', 'black knight', 'hero', 'hunter', 'nomad', 'morik the gladiator', 'smuggler', 'poacher', 'thief', 'undead jester', 'valkyrie', 'yaga the crone', 'wild warrior', 'baron brute', 'coldheart', 'doomhowl', 'dreadwing', 'fatality', 'haunter', 'incineron', 'menace', 'rocko', 'the axeorcist', 'the dreadorian', 'tirecz', 'tremorak', 'lizard sentinel', 'lizard snakecharmer', 'blue djinn', 'efreet', 'green djinn', 'marid', 'braindeath', 'bonelord', 'elder bonelord', 'gazer', 'mimic', 'stalker', 'wisp', 'minotaur archer', 'minotaur guard', 'minotaur mage', 'minotaur', 'banshee', 'betrayed wraith', 'blightwalker', 'bonebeast', 'crypt shambler', 'demon skeleton', 'dreadbeast', 'gargoyle', 'ghost', 'ghoul', 'ghostly apparition', 'grim reaper', 'gravelord oshuran', 'lich', 'lost soul', 'mummy', 'phantasm', 'pythius the rotten', 'skeleton warrior', 'spectre', 'tormented ghost', 'vampire', 'vampire bride', 'undead gladiator', 'zombie', 'mutated bat', 'mutated human', 'mutated rat', 'mutated tiger', 'werewolf', 'orc berserker', 'orc leader', 'orc rider', 'orc shaman', 'orc spearman', 'orc warlord', 'orc warrior', 'orc', 'brutus bloodbeard', 'pirate buccaneer', 'pirate corsair', 'pirate cutthroat', 'pirate ghost', 'pirate marauder', 'pirate skeleton', 'carniphila', 'haunted treeling', 'spit nettle', 'kongra', 'merlkin', 'sibang', 'serpent spawn', 'apocalypse', 'infernatil', 'verminor', 'annihilon', 'golgordan', 'hellgorak', 'latrivan', 'madareth', 'rift brood', 'rift lord', 'rift phantom', 'rift scythe', 'rift worm', 'ushuriel', 'zugurosh', 'ghazbaran', 'morgaroth', 'troll champion', 'frost troll', 'island troll', 'swamp troll', 'troll', 'acid blob', 'ancient scarab', 'azure frog', 'butterfly', 'bog raider', 'bug', 'centipede', 'cobra', 'coral frog', 'crimson frog', 'crystal spider', 'death blob', 'deathspawn', 'defiler', 'giant spider', 'green frog', 'larva', 'mercury blob', 'orchid frog', 'poison spider', 'scarab', 'scorpion', 'slime2', 'slime', 'snake', 'son of verminor', 'spider', 'tarantula', 'the abomination', 'toad', 'wasp') as $v)
						if ($v == substr($s, 0, strlen($v))) {
							$e['name'] = 'This character name is already used. Please select another one!';
							break;
						}
					if (!isset($e['name'])) {
						foreach (array('puta', 'caralho', 'karalho', 'porra', 'buceta', 'corno', 'simone','training', 'devovorga', 'energized raging mage', 'ot', 'otserv', 'thunder', 'serve', 'trimera', 'drugo', 'drugovich', 'adm', 'godsao','godzao','godsinho','GoDzinho','godzinho','Godzinho','god','GoD','GOd','God','GOD','mage mad', 'mad mage', 'abyssador', 'armadile', 'cliff strider', 'crystalcrusher', 'damaged crystal golem', 'deathstrike', 'stone devourer', 'hideous fungus', 'humongous fungus', 'humorless fungus', 'enraged crystal golem', 'wiggler', 'lost berserker', 'lava golem', 'magma crawler', 'weeper', 'gnomevil', 'ironblight', 'orewalker', 'vulcongra', 'mushroom sniffer', 'parasite', 'infected weeper', 'doctor gnomedix', 'pythius the rotten', 'lady bug', 'manta ray', 'deepling worker', 'god alone', 'bazir', 'crawler', 'deepling guard', 'deepling spellsinger', 'deepling warrior', 'manta', 'shark', 'spidris', 'spitter', 'waspoid', 'kollos', 'insectoid scout', 'calamary', 'fish', 'hive overseer', 'jellyfish', 'northern pike', 'spidris elite', 'swarmer', 'insectoid worker', 'deepling brawler', 'deepling master librarian', 'deepling tyrant', 'deepling elite', 'floor blob', 'hive pore', 'lesser swarmer', 'adi', 'adinmin', 'adimi', 'adimin', 'task', 'task clous', 'queen', 'queen eloise', 'game', " ' ", 'customer', 'support', 'fuck', 'haha', 'sux', ' abc', 'suck', 'noob', 'tutor', 'admin', 'account', 'gay', 'password', 'manager', " ' ", 'gm','cm', 'aff ', 'god ', 'abc', 'tutor', 'game', 'admin', 'the', 'rashid', 'alesar', 'benjamin', 'suzy', 'tajis', 'sarina', 'poach', 'Nah Bob', 'galuna', 'elane', 'chonndur', 'chondur', 'addoner', 'haroun', 'yaman', 'towncryer', 'jaul', 'obujos', 'hive overseer', 'crawler', 'deepling guard', 'deepling spellsinger', 'deepling warrior', 'lady bug', 'manta', 'shark', 'spidris', 'spitter', 'waspoid', 'kollos', 'lion event', 'tiger event', 'gerador azul i', 'gerador azul ii', 'gerador azul iii', 'gerador vermelho i', 'gerador vermelho ii', 'gerador vermelho iii', 'zombie event', 'emperium', 'guard', 'feromous', 'abomination fury', 'alvo', 'gate', 'elder beholder', 'trainer', 'beholder', 'yalaharian', 'protect cube', 'protect castle', 'protect statue', 'castle gates', 'lizard magistratus', 'lizard noble', 'askarak demon', 'askarak lord', 'askarak prince', 'bog frog', 'clay guardian', 'crystal wolf', 'death priest', 'deepling scout', 'desperate white deer', 'diamond servant', 'dromedary', 'donkey', 'elder mummy', 'enraged white deer', 'feverish citizen', 'filth toad', 'firestarter', 'ghoulish hyaena', 'golden servant', 'grave guard', 'groam', 'honour guard', 'horestis', 'horse', 'incredible old witch', 'insectoid scout', 'iron servant', 'kraknaknork', 'kraknaknork demon', 'running elite orc guard', 'sacred spider', 'sandstone scorpion', 'shaburak demon', 'shaburak lord', 'shaburak prince', 'slug', 'spider queen', 'starving wolf', 'thornfire wolf', 'tomb servant', 'weakened demon', 'wild dog', 'white deer', 'yielothax', 'boar', 'cake golem', 'crustacea gigantica', 'draptor', 'ghost rat', 'midnight panther', 'slime puddle', 'spectral scum', 'stampor', 'undead cavebear', 'brimstone bug', 'draken abomination', 'draken elite', 'fury of the emperor', 'glitterscale', 'heoni', 'lizard abomination', 'snake god essence', 'scorn of the emperor', 'snake think', 'souleater', 'spite of the emperor', 'wrath of the emperor', 'draken spellweaver', 'draken warmaster', 'ghastly dragon', 'gnarlhound', 'insect swarm', 'killer caiman', 'lancer beetle', 'lizard chosen', 'lizard dragon priest', 'lizard high guard', 'lizard legionnaire', 'lizard zaogun', 'orc marauder', 'sandcrawler', 'terramite', 'undead prospector', 'skeleton', 'wailing widow', 'berserker chicken', 'boogey', 'bride of night', 'demon parrot', 'dirtbeard', 'deer', 'esmeralda', 'essence of darkness', 'evil mastermind', 'evil sheep lord', 'evil sheep', 'fahim the wise', 'hide', 'hot dog', 'killer rabbit', 'medusa', 'mephiles', 'merikh the slaughterer', 'monstor', 'rottie the rotworm', 'shardhead', 'the bloodtusk', 'the many', 'the noxious spawn', 'the snapper', 'badger', 'bat', 'bear', 'black sheep', 'blood crab', 'blood crab underwater', 'carrion worm', 'cat', 'cave rat', 'chicken', 'cockroach', 'crab', 'crocodile', 'dire penguin', 'dog', 'elephant', 'flamingo', 'hyaena', 'husky', 'kitty', 'lion', 'mad sheep', 'mammoth', 'panda', 'parrot', 'penguin', 'pig', 'polar bear', 'rabbit', 'rat', 'rotworm', 'seagull', 'sheep', 'silver rabbit', 'skunk', 'squirrel', 'terror bird', 'thornback tortoise', 'tiger', 'tortoise', 'tortoise anti-bot', 'war wolf', 'winter wolf', 'wolf', 'dwarf', 'dwarf guard', 'dwarf geomancer', 'dwarf soldier', 'quara pincher', 'quara predator', 'quara constrictor', 'quara hydromancer', 'quara mantassin', 'quara pincher scout', 'quara predator scout', 'quara constrictor scout', 'quara hydromancer scout', 'quara mantassin scout', 'sea serpent', 'young sea serpent', 'achad', 'axeitus headbanger', 'bloodpaw', 'bovinus', 'colerian the barbarian', 'cursed gladiator', 'frostfur', 'orcus the cruel', 'rocky', 'the hairy one', 'avalanche', 'drasilla', 'grimgor guteater', 'kreebosh the exile', 'slim', 'spirit of earth', 'spirit of fire', 'spirit of water', 'the dark dancer', 'the hag', 'darakan the executioner', 'deathbringer', 'fallen mooh tah master ghar', 'gnorre chyllson', 'norgle glacierbeard', 'svoren the mad', 'the masked marauder', 'the obliverator', 'the pit lord', 'webster', 'flamethrower', 'hell hole', 'lavahole', 'dwarf dispenser', 'magicthrower', 'magic pillar', 'pillar', 'mechanical figher', 'plaguethrower', 'poisonthrower', 'shredderthrower', 'barbarian bloodwalker', 'barbarian brutetamer', 'barbarian headsplitter', 'barbarian skullhunter', 'arachir the ancient one', 'armenius', 'arthei', 'apprentice sheng', 'azerus', 'barbaria', 'battlemaster zunzu', 'big boss trolliver', 'blistering fire elemental', 'boreth', 'captain jones', 'chizzoron the distorter', 'the countess sorrow', 'cublarc the plunderer', 'deadeye devious', 'demodras', 'dharalion', 'diblis the fair', 'diseased bill', 'diseased dan', 'diseased fred', 'dracola', 'dreadmaw', 'earth overlord', 'energy overlord', 'fernfang', 'ferumbras', 'fire overlord', 'fleabringer', 'foreman kneebiter', 'freegoiz', 'general murius', 'grandfather tridian', 'grand mother foulscale', 'grorlam', 'hairman the huge', 'lizard templar', 'ice overlord', 'inky', 'koshei the deathless', 'lersatio', 'lethal lissy', 'lizard gate guardian', 'lord of the elements', 'mad technomancer', 'man in the cave', 'marziel', 'massacre', 'mooh tah master', 'mr. punish', 'munster', 'necropharus', 'ron the ripper', 'renegade orc', 'rotworm queen', 'rukor zad', 'shadow of boreth', 'shadow of lersatio', 'shadow of marziel', 'shard of corruption', 'sharptooth', 'sir valorcrest', 'splasher', 'smuggler baron silvertoe', 'stonecracker', 'the blightfather', 'the big bad one', 'the collector', 'the count', 'the evil eye', 'the frog prince', 'the handmaiden', 'the horned fox', 'the imperor', 'the old widow', 'the plasmother', 'the voice of ruin', 'the weakened count', 'thul', 'tiquandas revenge', 'ungreez', 'warlord ruzad', 'yakchal', 'yalahari', 'yeti', 'zarabustor', 'zebelon duskbringer', 'zulazza the corruptor', 'chakoya toolshaper', 'chakoya tribewarden', 'chakoya windcaller', 'dark torturer', 'demon', 'destroyer', 'diabolic imp', 'fire devil', 'gozzler', 'hand of cursed fate', 'hellfire fighter', 'hellhound', 'hellspawn', 'juggernaut', 'nightmare', 'nightmare scion', 'nightstalker', 'plaguesmith', 'bazir', 'orshabaal', 'zoralurk', 'frost dragon', 'wyrm', 'dragon lord', 'dragon', 'hydra', 'dragon hatchling', 'dragon lord hatchling', 'frost dragon hatchling', 'undead dragon', 'wyvern', 'blazing fire elemental', 'charged energy elemental', 'earth elemental', 'energy elemental', 'fire elemental', 'jagged earth elemental', 'massive earth elemental', 'massive energy elemental', 'massive fire elemental', 'massive water elemental', 'muddy earth elemental', 'overcharged energy elemental', 'roaring water elemental', 'slick water elemental', 'water elemental', 'elf arcanist', 'elf scout', 'elf', 'bones', 'deathslicer', 'exp bug', 'eye of the seven', 'fluffy', 'gamemaster', 'goblin demon', 'grynch clan goblin', 'hacker', 'the halloween hare', 'the ruthless herald', 'minishabaal', 'primitive', 'servant golem', 'tha exp carrier', 'the mutated pumpkin', 'tibia bug', 'undead minion', 'ashmunrah', 'arkhothep', 'dipthrah', 'mahrdis', 'morguthis', 'omruc', 'rahemos', 'thalas', 'vashresamun', 'frost giant', 'frost giantess', 'cyclops smith', 'cyclops drone', 'behemoth', 'cyclops', 'ice golem', 'the old whopper', 'stone golem', 'war golem', 'worker golem', 'damaged worker golem', 'goblin leader', 'goblin scavenger', 'goblin', 'goblin assassin', 'dworc fleshhunter', 'dworc venomsniper', 'dworc voodoomaster', 'acolyte of the cult', 'adept of the cult', 'amazon', 'crazed beggar', 'enlightened of the cult', 'novice of the cult', 'dark monk', 'monk', 'dark apprentice', 'dark magician', 'fury', 'gladiator', 'gang member', 'ice witch', 'infernalist', 'mad scientist', 'warlock', 'witch', 'necromancer', 'priestess', 'assasin', 'bandit', 'black knight', 'hero', 'hunter', 'nomad', 'morik the gladiator', 'smuggler', 'poacher', 'thief', 'undead jester', 'valkyrie', 'yaga the crone', 'wild warrior', 'baron brute', 'coldheart', 'doomhowl', 'dreadwing', 'fatality', 'haunter', 'incineron', 'menace', 'rocko', 'the axeorcist', 'the dreadorian', 'tirecz', 'tremorak', 'lizard sentinel', 'lizard snakecharmer', 'blue djinn', 'efreet', 'green djinn', 'marid', 'braindeath', 'bonelord', 'elder bonelord', 'gazer', 'mimic', 'stalker', 'wisp', 'minotaur archer', 'minotaur guard', 'minotaur mage', 'minotaur', 'banshee', 'betrayed wraith', 'blightwalker', 'bonebeast', 'crypt shambler', 'demon skeleton', 'dreadbeast', 'gargoyle', 'ghost', 'ghoul', 'ghostly apparition', 'grim reaper', 'gravelord oshuran', 'lich', 'lost soul', 'mummy', 'phantasm', 'pythius the rotten', 'skeleton warrior', 'spectre', 'tormented ghost', 'vampire', 'vampire bride', 'undead gladiator', 'zombie', 'mutated bat', 'mutated human', 'mutated rat', 'mutated tiger', 'werewolf', 'orc berserker', 'orc leader', 'orc rider', 'orc shaman', 'orc spearman', 'orc warlord', 'orc warrior', 'orc', 'brutus bloodbeard', 'pirate buccaneer', 'pirate corsair', 'pirate cutthroat', 'pirate ghost', 'pirate marauder', 'pirate skeleton', 'carniphila', 'haunted treeling', 'spit nettle', 'kongra', 'merlkin', 'sibang', 'serpent spawn', 'apocalypse', 'infernatil', 'verminor', 'annihilon', 'golgordan', 'hellgorak', 'latrivan', 'madareth', 'rift brood', 'rift lord', 'rift phantom', 'rift scythe', 'rift worm', 'ushuriel', 'zugurosh', 'ghazbaran', 'morgaroth', 'troll champion', 'frost troll', 'island troll', 'swamp troll', 'troll', 'acid blob', 'ancient scarab', 'azure frog', 'butterfly', 'bog raider', 'bug', 'centipede', 'cobra', 'coral frog', 'crimson frog', 'crystal spider', 'death blob', 'deathspawn', 'defiler', 'giant spider', 'green frog', 'larva', 'mercury blob', 'orchid frog', 'poison spider', 'scarab', 'scorpion', 'slime2', 'slime', 'snake', 'son of verminor', 'spider', 'tarantula', 'the abomination', 'toad', 'wasp') as $v)
							if (strpos($s, $v) !== false) {
								$e['name'] = 'This character name is already used. Please select another one!';
								break;
							}
						if (!isset($e['name'])) {
							$ple = new Player($s, Player::LOADTYPE_NAME);
							if ($ple->isLoaded())
								$e['name'] = 'This character name is already used. Please select another one!';
						}
					}
				}
			}
		}
	}

	if (!isset($_POST['sex']) || ($_POST['sex'] != 'male' && $_POST['sex'] != 'female'))
		$e['sex'] = 'Please select the sex for your character!';

	if (count($voc) != 0 && (!isset($_POST['vocation']) || !is_numeric($_POST['vocation']) || !isset($voc[$_POST['vocation']])))
		$e['vocation'] = 'Please select the vocation for your character!';
*/
	$s = isset($_POST['email']) ? $_POST['email'] : '';

	if ($s == '')
		$e['email'] = 'Please enter your email address!';
	elseif (strlen($s) > 255)
		$e['email'] = 'Your email address is too long!';
	elseif (!filter_var($s, FILTER_VALIDATE_EMAIL))
		$e['email'] = 'This email address has an invalid format. Please enter a correct email address!';
	else {
		$accMailCheck = new Account($s, Account::LOADTYPE_MAIL);
		if ($accMailCheck->isLoaded())
			$e['email'] = 'This email address is already used. Please enter another email address!';
	}

	$s1 = isset($_POST['password1']) ? $_POST['password1'] : '';
	$s2 = isset($_POST['password2']) ? $_POST['password2'] : '';

	if (empty($s2))
		$e['pass'] = 'Please enter the password again!';
	elseif ($s1 != $s2)
		$e['pass'] = 'The two passwords do not match!';
	else {
		$err = array();
		if (strlen($s1) < 6 || strlen($s1) > 50)
			$err[] = 'The password must have at least 6 and less than 50 letters!';
		if (!ctype_alnum($s1))
			$err[] = 'The password contains invalid letters!';

		if (count($err) != 0) {
			$e['pass'] = '';
			for($i=0; $i < count($err); $i++)
				$e['pass'] .= ($i == 0 ? '' : '<br/>').$err[$i];
		}
	}

	if (count($e) != 0) {
		foreach ($e as $error)
			$main_content .= '<div class="alert alert-danger">'.$error.'</div>';
	} else {

		$reg_account = new Account();
		$reg_account->setName($_POST['accountName']);
		$reg_account->setPassword($_POST['password1']);
		$reg_account->setEMail($_POST['email']);

		$reg_account->setCreateDate(time());
		$reg_account->setCreateIP(Visitor::getIP());
		$reg_account->setFlag(Website::getCountryCode(long2ip(Visitor::getIP())));
		if (isset($config['site']['newaccount_premdays']) && $config['site']['newaccount_premdays'] > 0)
		{
			$reg_account->set("premdays", $config['site']['newaccount_premdays']);
			$reg_account->set("lastday", time());
		}
		$reg_account->save();
		
		/*
		if ($reg_account->getID() > 0) {
			$sample = 'Blank';
			$char_to_copy = new Player();
			$char_to_copy->find($sample);
			if (!$char_to_copy->isLoaded())
				die('Missing sample character ('.$sample.')');

			$char_to_copy->getItems()->load();
			$char_to_copy->setLookType(($_POST['sex'] == 'female' ? 136 : 128));
			$char_to_copy->setID(null); // save as new character
			$char_to_copy->setLastIP(0);
			$char_to_copy->setLastLogin(0);
			$char_to_copy->setLastLogout(0);
			$char_to_copy->setName($_POST['characterName']);
			$char_to_copy->setAccount($reg_account);
			$char_to_copy->setSex(($_POST['sex'] == 'female' ? 0 : 1));
			$char_to_copy->setVocation($voc);
			$char_to_copy->setCreateIP(Visitor::getIP());
			$char_to_copy->setCreateDate(time());
			$char_to_copy->setSave(); // make character saveable
			$char_to_copy->save(); // now it will load 'id' of new player
			if ($char_to_copy->isLoaded()) {
				$char_to_copy->saveItems();
			}
		} else die('Failed to create account.');
		*/

		$main_content = '<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Account Created</h3>
		</div>
		<div class="panel-body">';
		$main_content .= '<p>Your account and character has been created successfully.</p>
						 <p>Login <a href="?view=account">here</a>.</p>';
		$main_content .= '</div></div>';
		return;
	}

} else $_POST['step'] = '';

	$main_content .= '<form class="form-horizontal" id="registration" role="form" action="?view=register" method="post">';
	/* I don't for creating a player as the account is created	
	$main_content .= '
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Create Character</h3>
			</div>
			<div class="panel-body">
				<fieldset form="registration">	
					<div class="form-group">
						<label for="characterName" class="col-lg-3 control-label">Character Name</label>
						<div class="col-lg-8">
							<input type="text" class="form-control" id="characterName" name="characterName" required>
						</div>
					</div>
					<div class="form-group">
						<label for="select" class="col-lg-3 control-label">Vocation</label>
						<div class="col-lg-8">
							<select style="padding:5px" class="form-control" name="vocation">';
								for ($vocation = 1; $vocation <= count(Website::getVocations()); $vocation++) {
									$main_content .= '<option name="newcharvocation" value="'.$vocation.'">'.Website::getVocationName($vocation).'</option>';
								}
	$main_content .= '
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-3 control-label">Sex</label>
						<div class="col-lg-3">
							<label class="radio-inline"><input type="radio" name="sex" value="male" checked="checked">Male</label>
							<label class="radio-inline"><input type="radio" name="sex" value="female">Female</label>
						</div>
					</div>
				</fieldset>
			</div>
		</div>';
	*/
	$main_content .= '
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Create Account</h3>
			</div>
			<div class="panel-body">
				<fieldset form="registration">
					<div class="form-group" id="usernamevalidate">
						<label for="accountName" class="col-lg-3 control-label">Account Name</label>
						<div class="col-lg-8">
							<input type="text" class="form-control" id="accountName" name="accountName" maxlength="30" required>
							<ul class="input-requirements" style="margin-bottom: -1px">
								<li style="margin-top: 10px">At least 4 characters</li>
								<li>No more than 30 characters long</li>
								<li>Must only contain letters and numbers (no special characters)</li>
							</ul>
						</div>
					</div>

					<div class="form-group" id="passwordvalidate">
						<label for="password1" class="col-lg-3 control-label">Password</label>
						<div class="col-lg-8">
							<input type="password" class="form-control" id="password1" name="password1" required>
							<ul class="input-requirements" style="margin-bottom: -1px">
								<li style="margin-top: 10px">At least 6 characters</li>
								<li>No more than 50 characters long</li>
							</ul>
						</div>
					</div>

					<div class="form-group">
						<label for="password2" class="col-lg-3 control-label">Repeat Password</label>
						<div class="col-lg-8">
							<input type="password" class="form-control" id="password2" name="password2" maxlength="50" required>
						</div>
					</div>

					<div class="form-group">
						<label for="email" class="col-lg-3 control-label">E-mail Address</label>
						<div class="col-lg-8">
							<input type="text" class="form-control" id="email" name="email" maxlength="255" required>
						</div>
					</div>
					
					<div class="text-center">
						<button type="submit" class="btn btn-primary" name=step value=docreate>Submit</button>
						<a class="btn btn-default" href="?view=account">Cancel</a>
					</div>

				</fieldset>
			</div>
		</div>';
	$main_content .= '</form>
	<script src="js/validation.js"></script>';
