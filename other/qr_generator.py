#!/usr/bin/python3
import qrcode
import argparse

from pathlib import Path


def make_qr(order_type: int, url: str):
    qr = qrcode.QRCode(
        version=10,
        error_correction=qrcode.constants.ERROR_CORRECT_H,
        box_size=20,
        border=4,
    )
    url = 'http://' + url + '?id={}'
    url = url.format(order_type)
    qr.add_data(url)
    qr.make(fit=True)
    img = qr.make_image(fill_color="black", back_color="white")
    p = Path.cwd()

    img.save(p.parents[0] / 'figures' / 'qr_{}.png'.format(order_type))


if __name__ == '__main__':
    parser = argparse.ArgumentParser('QR generator for cinfdata ordering system')
    parser.add_argument('id', type=int)
    parser.add_argument('url', type=str)
    args = parser.parse_args()
    make_qr(args.id, args.url)
