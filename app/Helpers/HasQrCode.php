<?php

namespace App\Helpers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;


trait HasQrCode{

	/**
     * Génère et persiste le QR code en base64.
     *
     * @param  string  $payload  Contenu à encoder
     * @return string
     */
    public function generateAndSaveQrCode(string $payload): string
    {
        $png = QrCode::format('png')
            ->size(300)
            ->margin(1)
            ->generate($payload);

        return 'data:image/png;base64,' . base64_encode($png);
    }

    // /**
    //  * Retourne le QR code prêt pour une balise <img>.
    //  *
    //  * @return string|null
    //  */
    // public function getQrCodeImgAttribute(): ?string
    // {
    //     return $this->qr_code;
    // }






}