import datetime
import sys
import requests

def get_serial_number():

    today = datetime.date.today()
    return today.strftime("%Y%m%d01")

def create_unbound_config(domain_list, output_file):

    serial_number = get_serial_number()
    with open(output_file, 'w') as output:
        output.write(f'# Arquivo de configuração gerado automaticamente em {datetime.datetime.now()}\n\n')

        for domain in domain_list:
            domain = domain.strip()
            output.write(f'local-zone: "{domain}" always_nxdomain\n')

        output.write('\n# Fim do arquivo de configuração\n')

def main():

    url = 'https://seudominio.com.br/censura/exportar_dominios.php'
    response = requests.get(url)

    if response.status_code == 200:
        domain_list = response.text.split('\n')
        domain_list = [domain.strip() for domain in domain_list if domain.strip()]

        unbound_config_file = '/tmp/local-block.conf'

        create_unbound_config(domain_list, unbound_config_file)
        print("Arquivo de configuração para Unbound atualizado.")
    else:
        print("Falha ao obter os domínios.")

if __name__ == "__main__":
    main()
