<?php
class MusicDataSeeder extends Seeder
{
	public function run()
	{
		$programs = [
			'Reaktor',
			'Max/MSP',
			'Pure Data',
		];

		foreach ($programs as $programName) {
			$programData = ['name' => $programName];
			$program = MusicProgram::firstOrCreate($programData);
		}

		$plugins = [
			[
				'name' => 'reFX Nexus',
				'banks' => [
					'House Vol.1',
					'Dance Orchestra',
					'Perpetual Motion',
					'Dance Drums',
					'Store n’ Forward',
					'Stratosphere',
					'Analog',
					'Bigtone Signature',
					'Guitars',
					'Dance Vol.3',
					'Rauschwerk',
					'Pop',
					'HandsUp-Electro Bass',
					'Vocoder',
					'Vintage DrumKits',
					'HandsUp-Electro Bass Vol.2',
					'Omicron 2',
					'Future Arps',
					'Stratosphere 2',
					'Rauschwerk 2',
					'TV Movie Game',
					'Future Arps 2',
					'Hollywoood',
					'Dubstep-Electro Vol.1',
					'Apres-Ski',
					'Millennium Pop 2',
					'Future Arps 3',
					'HandsUp-Electro Bass Vol. 3',
					'FX',
					'Christmas',
					'Swedish House Vol. 1',
					'Sound oft he 90s',
					'Dubstep-Electro Vol. 2',
					'House Vol. 2',
					'Rauschwerk 3',
					'Hollywood 2',
					'Hardstyle 2',
					'Electro House Leads',
					'Dance Vol 1',
					'Minimal House',
					'Psytrance',
					'Hardstyle',
					'Bass',
					'Total Piano',
					'Minimal House 2',
					'Crank',
					'SID',
					'Dance Vol. 2',
					'Bigtone Signature 2',
					'Omicron',
					'NuElectro',
					'Crank 2',
					'Bigtone Signature 3',
					'ROM Extension',
					'FM',
					'Kamui',
					'Trance Elements',
					'HandsUp Leads Vol 1',
					'Classic Trance',
					'Omicron 3',
					'Millennium Pop',
					'Dark Planet',
					'Kamui 2',
					'Trance Leads',
					'7 Skies Trance',
					'Commercial Electro',
					'Halloween',
					'Hip Hop',
					'Progressive Tech House',
					'Bass 2Freaky Machines',
					'Trance Anthems',
					'Dubstep-Electro Vol.3',
					'Trap',
					'Christmas 2013',
				],
			],
			['name' => 'Vanguard', 'banks' => []],
			['name' => 'Massive', 'banks' => []],
			['name' => 'Sylenth 1', 'banks' => []],
			['name' => 'Sonic Academy’s A.N.A.', 'banks' => []],
			['name' => 'Virus TI 2', 'banks' => []],
			['name' => 'MONARK', 'banks' => []],
			['name' => 'ABSYNTH 5', 'banks' => []],
			['name' => 'FM8', 'banks' => []],
			['name' => 'REAKTOR PRISM', 'banks' => []],
			['name' => 'REAKTOR SPARK', 'banks' => []],
			['name' => 'RETRO MACHINES MK 2', 'banks' => []],
			['name' => 'VIENNA CONCERT GRAND', 'banks' => []],
			['name' => 'UPRIGHT PIANO', 'banks' => []],
			['name' => 'NEW YORK CONCERT GRAND', 'banks' => []],
			['name' => 'BERLIN CONCERT GRAND', 'banks' => []],
			['name' => 'SCARBEE CLAVINET/PIANET', 'banks' => []],
			['name' => 'SCARBEE MARK I', 'banks' => []],
			['name' => 'SCARBEE A-200', 'banks' => []],
			['name' => 'VINTAGE ORGANS', 'banks' => []],
			['name' => 'SESSION STRINGS', 'banks' => []],
			['name' => 'THE GIANT', 'banks' => []],
			['name' => 'WEST AFRICA', 'banks' => []],
			['name' => 'STUDIO DRUMMER', 'banks' => []],
			['name' => 'ABBEY ROAD 60s DRUMMER', 'banks' => []],
			['name' => 'BATTERY 4', 'banks' => []],
			['name' => 'Roland Gaia', 'banks' => []],
			['name' => 'Roland SH-201', 'banks' => []],
			['name' => 'V-Station', 'banks' => []],
			['name' => 'Z3ta+ 2', 'banks' => []],
			['name' => 'JP6K', 'banks' => []],
		];

		foreach ($plugins as $pluginData) {
			$banks = array_pull($pluginData, 'banks');

			$plugin = MusicPlugin::firstOrCreate($pluginData);

			foreach ($banks as $bankName) {
				$bank = MusicPluginBank::firstOrCreate([
					'name' => $bankName,
					'music_plugin_id' => $plugin->id,
				]);
			}
		}
	}
}
