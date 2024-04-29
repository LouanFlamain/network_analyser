<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\ReadXmlCommandService;

#[AsCommand(
    name: 'ReadXmlCommand',
    description: 'Add a short description for your command',
)]
class ReadXmlCommand extends Command
{
    private $deviceService;

    public function __construct(ReadXmlCommandService $deviceService)
    {
        $this->deviceService = $deviceService;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = realpath("./arp_scan_output.xml");
        if (file_exists($path)) {
            $xmlContent = file_get_contents($path);
            $xml = simplexml_load_string($xmlContent);
            if ($xml === false) {
                $output->writeln('Erreur lors du chargement du fichier XML');
                return Command::FAILURE; 
            }
            
            $json = json_encode($xml);
            $array = json_decode($json, TRUE);
            $timestamp = $array['info']['timestamp'];
            $mac_array = [];

            foreach($array['arp-entries']['arp-entry'] as $entry){
                $object = [
                    "addr_ip" => $entry['ip-address'],
                    "addr_mac" => $entry['mac-address'],
                    "device_name" => $entry['device-name'],
                    "timestamp" => $timestamp
                ];
                if(assert($entry['ip-address']) || assert($entry['mac-address']) || assert($entry['device-name'])){
                    var_dump("success");
                    $this->deviceService->createOrUpdateDevice($object);
                    array_push($mac_array, $object['addr_mac']);
                }else{
                    var_dump("echec");
                }
            }

            if(!empty($mac_array)){
                $this->deviceService->disableNonPresenceDevice($mac_array);
            }

            return Command::SUCCESS;
        } else {
            $output->writeln('Fichier non trouvé.');
            return Command::FAILURE;
        }
    }
    
}
