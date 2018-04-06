<?php

use BitWasp\Buffertools\Buffer;
use EthereumRawTx\Rlp\RlpDecoder;
use EthereumRawTx\Rlp\RlpEncoder;

describe("Rlp Decoder ", function () {

    it("the string dog", function () {

        $encodeValue = '83' . bin2hex('dog');

        /** @var Buffer $result */
        $result = RlpDecoder::decode(Buffer::hex($encodeValue));

        expect($result->getBinary())->equal('dog');
    });

    it("the list [ \"cat\", \"dog\" ] ", function () {

        $encodeValue = 'c8' . '83' . bin2hex('cat') .'83' . bin2hex('dog');

        /** @var Buffer[] $result */
        $result = RlpDecoder::decode(Buffer::hex($encodeValue));

        expect($result[0]->getBinary())->equal('cat');
        expect($result[1]->getBinary())->equal('dog');

    });

    it("the empty string ('null')", function () {

        $encodeValue = '80';

        /** @var Buffer $result */
        $result = RlpDecoder::decode(Buffer::hex($encodeValue));

        expect((bool)(int) $result->getBinary())->equal(false);
    });

    it("the empty list", function () {

        $encodeValue = 'c0';

        /** @var array $result */
        $result = RlpDecoder::decode(Buffer::hex($encodeValue));

        expect($result)->equal([]);
    });

    it("the integer 0", function () {

        $encodeValue = '00';

        /** @var Buffer $result */
        $result = RlpDecoder::decode(Buffer::hex($encodeValue));

        expect((int) $result->getInt())->equal(0);
    });

    it("the set theoretical representation of three,", function () {

        $encodeValue = 'c7' . 'c0' . 'c1' . 'c0' . 'c3' . 'c0' . 'c1' . 'c0';

        /** @var Buffer $result */
        $result = RlpDecoder::decode(Buffer::hex($encodeValue));

        expect($result)->equal([[],[[]],[[],[[]]]]);
    });

    it("the encoded integer 8", function () {

        /** @var Buffer $result */
        $result = RlpDecoder::decode(RlpEncoder::encode(Buffer::int(8)));

        expect((int) $result->getInt())->equal(8);
    });

    it("the encoded integer 1024 ('\x04\x00')", function () {

        $encodeValue = '82' . '04' . '00';

        /** @var Buffer $result */
        $result = RlpDecoder::decode(Buffer::hex($encodeValue));

        expect((int) $result->getInt())->equal(1024);
    });

    it("the string \"Lorem ipsum dolor sit amet, consectetur adipisicing elit\"", function () {

        $encodeValue = 'b8' . '38' . bin2hex('Lorem ipsum dolor sit amet, consectetur adipisicing elit');

        /** @var Buffer $result */
        $result = RlpDecoder::decode(Buffer::hex($encodeValue));

        expect($result->getBinary())->equal('Lorem ipsum dolor sit amet, consectetur adipisicing elit');
    });

    it("encode spec data", function () {
        /** @var Buffer[] $result */
        $result = RlpDecoder::decode(Buffer::hex('e2808085100000000094e9875966d7d6490592db866f815faf6fa94225a68080808080'));

        expect((int) $result[0]->getInt())->equal(0);
        expect((int) $result[1]->getInt())->equal(0);
        expect($result[2]->getHex())->equal('1000000000');
        expect($result[3]->getHex())->equal('e9875966d7d6490592db866f815faf6fa94225a6');
        expect((int) $result[4]->getInt())->equal(0);
        expect($result[5]->getHex())->equal('00');
    });


    it("an Ethereum transaction", function () {

        $data = "f90a068206c780830f42408080b909b36060604052341561000f57600080fd5b6040516060806109538339810160405280805191906020018051919060200180519150508181101561003d57fe5b4281101561004757fe5b60008054600160a060020a03191633600160a060020a031617905560048390556001829055600281905560038190556005805460ff191690557fa3e9c29f3df21edd1eb86fe6ed70e85cf557539b86a3e94e33ce37dbe3026ca3838383426040518085815260200184815260200183815260200182815260200194505050505060405180910390a1505050610872806100e16000396000f3006060604052600436106100e55763ffffffff7c01000000000000000000000000000000000000000000000000000000006000350416630b97bc8681146100ea5780630f7678721461010f578063164418131461012257806328f6a48a146101595780634423c5f11461016c578063454a2ab3146101b85780634c051f14146101e25780636f52a71114610211578063796b89b91461022457806382dd9b7714610237578063971c92a814610262578063bef4876b14610275578063c24a0f8b14610288578063d3924aaf1461029b578063e53dbd7a14610262578063efbe1c1c146102ae575b600080fd5b34156100f557600080fd5b6100fd6102c1565b60405190815260200160405180910390f35b341561011a57600080fd5b6100fd6102c7565b341561012d57600080fd5b6101356102cd565b60405180848152602001838152602001828152602001935050505060405180910390f35b341561016457600080fd5b6100fd6102dd565b341561017757600080fd5b6101826004356102e3565b604051600160a060020a039094168452602084019290925260408084019190915290151560608301526080909101905180910390f35b34156101c357600080fd5b6101ce600435610328565b604051901515815260200160405180910390f35b34156101ed57600080fd5b6101f56105f8565b604051600160a060020a03909116815260200160405180910390f35b341561021c57600080fd5b6100fd610673565b341561022f57600080fd5b6100fd610679565b341561024257600080fd5b61024a61067d565b60405191825260208201526040908101905180910390f35b341561026d57600080fd5b6100fd6106ff565b341561028057600080fd5b6101ce610704565b341561029357600080fd5b6100fd61070d565b34156102a657600080fd5b6101f5610713565b34156102b957600080fd5b6101ce610722565b60015481565b60045481565b6001546002546003549192909190565b60065490565b60068054829081106102f157fe5b60009182526020909120600490910201805460018201546002830154600390930154600160a060020a039092169350919060ff1684565b600080600254421115801561033f57506001544210155b151561034a57600080fd5b60005433600160a060020a039081169116141561036657600080fd5b61036e61067d565b9150508083101561048f57600680546001810161038b83826107c7565b9160005260206000209060040201600060806040519081016040908152600160a060020a0333168252602082018890524290820152600060608201529190508151815473ffffffffffffffffffffffffffffffffffffffff1916600160a060020a039190911617815560208201518160010155604082015181600201556060820151600391909101805460ff1916911515919091179055507fcfaabf79e49f480cc0bc57471cd9623f197f621fea8ace13d5afc4cd310b5e119050838233426040518085815260200184815260200183600160a060020a0316600160a060020a0316815260200182815260200194505050505060405180910390a1600091506105f2565b60068054600181016104a183826107c7565b9160005260206000209060040201600060806040519081016040908152600160a060020a0333168252602082018890524290820152600160608201529190508151815473ffffffffffffffffffffffffffffffffffffffff1916600160a060020a039190911617815560208201518160010155604082015181600201556060820151600391909101805460ff1916911515919091179055507ffc3d557e46010cdf2a5a80a495f308239f5be888eb5bb151be68d5c369728b249050838233426040518085815260200184815260200183600160a060020a0316600160a060020a0316815260200182815260200194505050505060405180910390a1601e600254034211156105ed5742601e810160028190557f8d2f4acb8d87f68ceb085b64b3e33f5e9627f6f151873dcab9a83bfa589ac0ba9160405191825260208201526040908101905180910390a15b600191505b50919050565b6006546000905b801561066f5760068054600019830190811061061757fe5b600091825260209091206003600490920201015460ff16156106665760068054600019830190811061064557fe5b6000918252602090912060049091020154600160a060020a0316915061066f565b600019016105ff565b5090565b60035481565b4290565b60065460009081905b80156106f45760068054600019830190811061069e57fe5b600091825260209091206003600490920201015460ff16156106eb576006805460001983019081106106cc57fe5b90600052602060002090600402016001015492508260010191506106fa565b60001901610686565b60045491505b509091565b601e81565b60055460ff1681565b60025481565b600054600160a060020a031681565b6000806002544211151561073557600080fd5b60055460ff161561074557600080fd5b6005805460ff1916600117905561075a61067d565b5090507f099d7286b4d3c895f712c3ba7ea61459fcece0415373b7ff952cfacbda24b08a6107866105f8565b826002546040518084600160a060020a0316600160a060020a03168152602001838152602001828152602001935050505060405180910390a1600191505090565b8154818355818115116107f3576004028160040283600052602060002091820191016107f391906107f8565b505050565b61084391905b8082111561066f57805473ffffffffffffffffffffffffffffffffffffffff19168155600060018201819055600282015560038101805460ff191690556004016107fe565b905600a165627a7a72305820492727fec9cabeb28f9ad9a5dc9d9aa3adc88436feedaab2b34599bd8db152930029000000000000000000000000000000000000000000000000000000000000000a000000000000000000000000000000000000000000000000000000005ac6443f000000000000000000000000000000000000000000000000000000005ac66e6f830164bea0da224e6ff0c105a6dec77e0b15dde2e783c6d1ef74830736b8a10b60d1b63d9da00be83efafb771ea42a5e0816abb5b1eaeb0ce281ebfce847f92129bcb83b0901";

        /** @var Buffer[] $result */
        $result = RlpDecoder::decode(Buffer::hex($data));

        expect(\count($result))->equal(9);

        $hex = [];
        foreach ($result as $buffer) {
            $hex[] = $buffer->getHex();
        }

        expect($hex)->equal([
            '06c7',
            '00',
            '0f4240',
            '00',
            '00',
            '6060604052341561000f57600080fd5b6040516060806109538339810160405280805191906020018051919060200180519150508181101561003d57fe5b4281101561004757fe5b60008054600160a060020a03191633600160a060020a031617905560048390556001829055600281905560038190556005805460ff191690557fa3e9c29f3df21edd1eb86fe6ed70e85cf557539b86a3e94e33ce37dbe3026ca3838383426040518085815260200184815260200183815260200182815260200194505050505060405180910390a1505050610872806100e16000396000f3006060604052600436106100e55763ffffffff7c01000000000000000000000000000000000000000000000000000000006000350416630b97bc8681146100ea5780630f7678721461010f578063164418131461012257806328f6a48a146101595780634423c5f11461016c578063454a2ab3146101b85780634c051f14146101e25780636f52a71114610211578063796b89b91461022457806382dd9b7714610237578063971c92a814610262578063bef4876b14610275578063c24a0f8b14610288578063d3924aaf1461029b578063e53dbd7a14610262578063efbe1c1c146102ae575b600080fd5b34156100f557600080fd5b6100fd6102c1565b60405190815260200160405180910390f35b341561011a57600080fd5b6100fd6102c7565b341561012d57600080fd5b6101356102cd565b60405180848152602001838152602001828152602001935050505060405180910390f35b341561016457600080fd5b6100fd6102dd565b341561017757600080fd5b6101826004356102e3565b604051600160a060020a039094168452602084019290925260408084019190915290151560608301526080909101905180910390f35b34156101c357600080fd5b6101ce600435610328565b604051901515815260200160405180910390f35b34156101ed57600080fd5b6101f56105f8565b604051600160a060020a03909116815260200160405180910390f35b341561021c57600080fd5b6100fd610673565b341561022f57600080fd5b6100fd610679565b341561024257600080fd5b61024a61067d565b60405191825260208201526040908101905180910390f35b341561026d57600080fd5b6100fd6106ff565b341561028057600080fd5b6101ce610704565b341561029357600080fd5b6100fd61070d565b34156102a657600080fd5b6101f5610713565b34156102b957600080fd5b6101ce610722565b60015481565b60045481565b6001546002546003549192909190565b60065490565b60068054829081106102f157fe5b60009182526020909120600490910201805460018201546002830154600390930154600160a060020a039092169350919060ff1684565b600080600254421115801561033f57506001544210155b151561034a57600080fd5b60005433600160a060020a039081169116141561036657600080fd5b61036e61067d565b9150508083101561048f57600680546001810161038b83826107c7565b9160005260206000209060040201600060806040519081016040908152600160a060020a0333168252602082018890524290820152600060608201529190508151815473ffffffffffffffffffffffffffffffffffffffff1916600160a060020a039190911617815560208201518160010155604082015181600201556060820151600391909101805460ff1916911515919091179055507fcfaabf79e49f480cc0bc57471cd9623f197f621fea8ace13d5afc4cd310b5e119050838233426040518085815260200184815260200183600160a060020a0316600160a060020a0316815260200182815260200194505050505060405180910390a1600091506105f2565b60068054600181016104a183826107c7565b9160005260206000209060040201600060806040519081016040908152600160a060020a0333168252602082018890524290820152600160608201529190508151815473ffffffffffffffffffffffffffffffffffffffff1916600160a060020a039190911617815560208201518160010155604082015181600201556060820151600391909101805460ff1916911515919091179055507ffc3d557e46010cdf2a5a80a495f308239f5be888eb5bb151be68d5c369728b249050838233426040518085815260200184815260200183600160a060020a0316600160a060020a0316815260200182815260200194505050505060405180910390a1601e600254034211156105ed5742601e810160028190557f8d2f4acb8d87f68ceb085b64b3e33f5e9627f6f151873dcab9a83bfa589ac0ba9160405191825260208201526040908101905180910390a15b600191505b50919050565b6006546000905b801561066f5760068054600019830190811061061757fe5b600091825260209091206003600490920201015460ff16156106665760068054600019830190811061064557fe5b6000918252602090912060049091020154600160a060020a0316915061066f565b600019016105ff565b5090565b60035481565b4290565b60065460009081905b80156106f45760068054600019830190811061069e57fe5b600091825260209091206003600490920201015460ff16156106eb576006805460001983019081106106cc57fe5b90600052602060002090600402016001015492508260010191506106fa565b60001901610686565b60045491505b509091565b601e81565b60055460ff1681565b60025481565b600054600160a060020a031681565b6000806002544211151561073557600080fd5b60055460ff161561074557600080fd5b6005805460ff1916600117905561075a61067d565b5090507f099d7286b4d3c895f712c3ba7ea61459fcece0415373b7ff952cfacbda24b08a6107866105f8565b826002546040518084600160a060020a0316600160a060020a03168152602001838152602001828152602001935050505060405180910390a1600191505090565b8154818355818115116107f3576004028160040283600052602060002091820191016107f391906107f8565b505050565b61084391905b8082111561066f57805473ffffffffffffffffffffffffffffffffffffffff19168155600060018201819055600282015560038101805460ff191690556004016107fe565b905600a165627a7a72305820492727fec9cabeb28f9ad9a5dc9d9aa3adc88436feedaab2b34599bd8db152930029000000000000000000000000000000000000000000000000000000000000000a000000000000000000000000000000000000000000000000000000005ac6443f000000000000000000000000000000000000000000000000000000005ac66e6f',
            '0164be',
            'da224e6ff0c105a6dec77e0b15dde2e783c6d1ef74830736b8a10b60d1b63d9d',
            '0be83efafb771ea42a5e0816abb5b1eaeb0ce281ebfce847f92129bcb83b0901',
        ]);

    });
});