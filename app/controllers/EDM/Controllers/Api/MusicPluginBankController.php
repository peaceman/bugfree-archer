<?php
namespace EDM\Controllers\Api;

use EDM\Controllers\AuthenticatedBaseController;
use EDM\MusicPluginBank\ValidationRules;
use Log;
use MusicPluginBank;
use Review;
use Validator;

class MusicPluginBankController extends AuthenticatedBaseController
{
	public function index()
	{
		$query = MusicPluginBank::accepted()->asUser($this->user);

		if ($this->request->has('q')) {
			$query->where('name', 'like', $this->request->get('q'));
		}

		$musicPluginBanks = $query->get();
		return $this->response->json($musicPluginBanks);
	}

	public function store()
	{
		$validationRules = (new ValidationRules\NewPluginBankFromUserInput())
			->getValidationRules();
		$inputData = $this->request->only(array_keys($validationRules));
		$validator = Validator::make($inputData, $validationRules);

		if ($validator->fails()) {
			return $this->response->json(['errors' => $validator->errors()->toArray()], 400);
		}

		$bank = new MusicPluginBank($inputData);
		$bank->userTrackingSession()->associate($this->user->fetchLastTrackingSession());

		if (!$bank->save()) {
			Log::error('failed to store a user supplied music bank', ['music_bank' => $bank->getAttributes()]);
			return $this->response->json(['errors' => ['unexpected system failure']], 500);
		}

		$bank->review()->save(new Review());
		return $this->response->json($bank, 201);
	}
}
