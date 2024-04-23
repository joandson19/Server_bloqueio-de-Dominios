import os
import datetime
import sys
import requests

def get_serial_number():

    today = datetime.date.today()
    return today.strftime("%Y%m%d01")

def create_rpz_zone_file(domain_list, output_file, var_domain):

    serial_number = get_serial_number()
    with open(output_file, 'w') as output:
        output.write(f"$TTL 1H\n@       IN      SOA LOCALHOST. {var_domain}. (\n")
        output.write(f"                {serial_number}      ; Serial\n")
        output.write("                1h              ; Refresh\n")
        output.write("                15m             ; Retry\n")
        output.write("                30d             ; Expire\n")
        output.write("                2h              ; Negative Cache TTL\n        )\n")
        output.write(f"        NS  {var_domain}.\n\n")

        for domain in domain_list:
            domain = domain.strip()
            output.write(f"{domain} IN CNAME .\n")
            output.write(f"*.{domain} IN CNAME .\n")

def main(var_domain):

    url = 'https://seudominio.com.br/censura/exportar_dominios.php'
    response = requests.get(url)

    if response.status_code == 200:
        domain_list = response.text.split('\n')
        domain_list = [domain.strip() for domain in domain_list if domain.strip()]

        rpz_zone_file = '/var/cache/bind/rpz/db.rpz.zone.hosts'

        create_rpz_zone_file(domain_list, rpz_zone_file, var_domain)
        print("Arquivo de zona RPZ atualizado.")
    else:
        print("Falha ao obter os domínios.")

if __name__ == "__main__":
    if len(sys.argv) != 2:
        print("Uso: python3 script.py sub.dominio.com.br")
        sys.exit(1)
    main(sys.argv[1])
