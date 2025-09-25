import requests
import mysql.connector
from mysql.connector import Error

def get_indodax_tickers():
    """Mengambil data ticker dari API publik Indodax."""
    try:
        response = requests.get("https://indodax.com/api/tickers")
        response.raise_for_status()  # Akan menimbulkan pengecualian untuk kode status HTTP yang buruk
        return response.json()
    except requests.exceptions.RequestException as e:
        print(f"Error saat mengambil data dari Indodax: {e}")
        return None

def save_tickers_to_mysql(tickers_data):
    """Menyimpan data ticker ke database MySQL."""
    if not tickers_data:
        return

    try:
        connection = mysql.connector.connect(
            host='localhost',          # Ganti dengan host database Anda jika berbeda
            database='indodax_data',   # Nama database Anda
            user='root',               # Nama pengguna database Anda
            password=''   # Ganti dengan kata sandi database Anda
        )

        if connection.is_connected():
            cursor = connection.cursor()
            tickers = tickers_data.get('tickers', {})
            
            for pair, data in tickers.items():
                # Mengganti nama kunci volume agar sesuai dengan kolom tabel
                vol_base = data.get(f"vol_{pair.split('_')[0]}", 0)
                vol_quote = data.get(f"vol_{pair.split('_')[1]}", 0)

                query = """INSERT INTO tickers (pair, high, low, vol_base, vol_quote, last, buy, sell, server_time)
                           VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)"""
                
                values = (
                    pair,
                    data.get('high'),
                    data.get('low'),
                    vol_base,
                    vol_quote,
                    data.get('last'),
                    data.get('buy'),
                    data.get('sell'),
                    data.get('server_time')
                )
                
                cursor.execute(query, values)

            connection.commit()
            print(f"{cursor.rowcount} baris berhasil dimasukkan.")

    except Error as e:
        print(f"Error saat terhubung ke MySQL: {e}")

    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()
            print("Koneksi MySQL ditutup.")

if __name__ == "__main__":
    indodax_data = get_indodax_tickers()
    if indodax_data:
        save_tickers_to_mysql(indodax_data)