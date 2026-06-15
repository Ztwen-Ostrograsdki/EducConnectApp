<?php

namespace App\Livewire\Traits;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait BeninPhoneValidation{

	public string $contacts = '';

	public function validatePhoneNumber()
	{
		$this->resetErrorBag('contacts');

		// 1. Vérifier si le champ est vide
		if (empty($this->contacts)) {
			throw ValidationException::withMessages([
				'contacts' => "Vous devez renseigner votre contact !"
			]);
		}

		// 2. Séparer les contacts par des tirets (fonctionne même s'il n'y a qu'un seul numéro)
		$parts = explode("-", $this->contacts);

		// 3. Valider chaque numéro individuellement
		foreach ($parts as $number) {
			
			// trim() enlève les espaces accidentels (ex: "0102030405 - 0102030406")
			$number = trim($number); 

			$validator = Validator::make(
				['contacts' => $number],
				['contacts' => ['required', 'numeric', 'starts_with:01', 'digits:10']]
			);

			if ($validator->fails()) {
				// Lève une exception qui stoppe IMMÉDIATEMENT l'exécution du code 
				// Livewire va l'attraper et afficher l'erreur sur ton champ 'contacts'
				throw ValidationException::withMessages([
					'contacts' => "Chaque numéro doit contenir exactement 10 chiffres, commencer par 01, et être séparé par des tirets si multiples."
				]);
			}
		}

		return true; // Si on arrive ici, tout est valide !
	}


	public function validatePhoneNumberSilently(string $contacts): ?string
	{
		if (empty($contacts)) {
			return "Vous devez renseigner votre contact !";
		}

		$parts = explode("-", $contacts);

		foreach ($parts as $number) {
			$number = trim($number);

			$validator = Validator::make(
				['contacts' => $number],
				['contacts' => ['required', 'numeric', 'starts_with:01', 'digits:10']]
			);

			if ($validator->fails()) {
				return "Chaque numéro doit contenir exactement 10 chiffres, commencer par 01, et être séparé par des tirets si multiples.";
			}
		}

		return null; 
	}


	private function validateEmailSilently(?string $email): ?string
	{
		// Email optionnel — si vide, on laisse passer
		if (empty($email)) {
			return null;
		}

		$validator = Validator::make(
			['email' => $email],
			['email' => ['required', 'string', 'email:rfc,dns', 'max:255']]
		);

		if ($validator->fails()) {
			return "L'adresse email \"{$email}\" est invalide.";
		}

		return null;
	}
}