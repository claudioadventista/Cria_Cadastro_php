<?php
/*

Esse codigo cria cadastros num banco mysql, para testes de sistemas.
uma tabela, e 10 colunas.
algumas colunas estao relacionada uma com a outra para parecer mais realista.
a coluna sexo, esta relacionada com a coluna nome.
a coluna cpf, esta relacionada com a coluna estado.
a coluna estado esta relacionada com a coluna local de nascimento e ddd do telefone.
a coluna data de nascimento, esta relacionada com o ano bissexto, e o estado civil.

algumas colunas foram programadas para nao se repetir.
a coluna cpf nao pode conter em mais de um cadastro.
a coluna telefone nao pode conter em mais de um cadastro.
a coluna nome, sexo, local de nascimento, estado, ano bissexto e estado civil, pode ser repetida inumeras vezes.
a coluna data de nascimento foi programada para nao gerar uma data inferior a data atual.
a coluna idade, nao esta na tabela, ela e calcula com os dados da data de nascimento e a dada atual.

*/

// informacao para se conectar ao banco mysql
$host = 'localhost';
$user = 'root';
$pass = '';
$database = 'cadastro';

// realiza a CONEXAO
$conn = mysqli_connect($host, $user, $pass, $database);

// EXCLUI TUDO
if(isset($_POST['excluir'])){
    $insere = "DELETE FROM cadastrar ";
    $query_cadastro = mysqli_query($conn, $insere);
}

// recebe o VALOR DO FORMULARIO
$cadastro = filter_input(INPUT_POST, "totalCriarCadastro", FILTER_SANITIZE_STRING);
if(isset($_POST['totalCriarCadastro'])){

    $total = trim($_POST['totalCriarCadastro']);
    $sorteado = "";

    // array dos NOMES MASCULINOS
    $sorteioNome = ["Amaro","André","josé","José Maria","João","Paulo","José maria","António","Bernardo","Baltazar","Clovis",
    "Caetano","Célio","Davi","Denis","Duarte","Eudes","Esdras","Fernando","Kaio","Luiz","Lúcio","Lenildo","Marcelo","Nivaldo",
    "Otávio","Ricardo","Renato","Severino","Sebastião","Tiago","Ulisses","Valdomiro","Ziraldo"];

    // array dos SEGUNDOS NOMES MASCULINOS
    $sorteioSegundoNome = [""," Claudio"," Roberto"," Carlos"," Pedro"," Xavier"];

    // array dos SOBRENOMES MASCULINOS
    $sorteioSobrenome = [" da silva"," de Brito"," de Lima"];

    // array dos NOMES FEMININOS
    $sorteioNomeFeminino = ["Amara","Maria","Joaona","Ana","Beatriz","Eliana","Fernanda","Ilma","Joselha","Késia","Luciana","Marta","Nayara","Olivia",
    "Penélope","Quésia","Renata","Rita","Rosângela","Samara","Tâmires","Valquiria","Zilda", "Ana Maria","Maria José"];
    
    // array dos SEGUNDOS NOMES FEMININOS
    $sorteioSegundoNomeFeminino = [""," Claudia"," Roberta"," Carla"," Marta"," Lindalva"," Silva"];
    
    // array dos SOBRENOMES FEMININOS
    $sorteioSobrenomeFeminino = [" da silva"," de Brito"," de Lima"];
     
    // array dos MESES DO ANO
     $sorteioMes = ["01","02","03","04","05","06","07","08","09","10","11","12"];

    // ARRAY DOS SEXOS
    $sorteioSexo = ["M","F"];

    $totRepetido = 0;
    $contaDataMaior = 0;
    $contaTelefone = 0;
    $estado = "";

     // funcao sorteia o MES
    // sortearMes($sorteioMes);
     function sortearMes($sorteioMes){
        $indice = array_rand($sorteioMes);
        return $sorteioMes[$indice];
    }
    // funcao sorteia os NOMES MASCULINOS
    function sortearNome($sorteioNome){
        $indice = array_rand($sorteioNome);
        return $sorteioNome[$indice];
    }

    // funcao sorteia os SEGUNDOS NOMES MASCULINOS
    function sortearSegundoNome($sorteioSegundoNome){
        $indice = array_rand($sorteioSegundoNome);
        return $sorteioSegundoNome[$indice];
    }

    // funcao sorteia os SOBRENOMES MASCULINOS
    function sortearSobrenome($sorteioSobrenome){
        $indice = array_rand($sorteioSobrenome);
        return $sorteioSobrenome[$indice];
    }

    // funcao sorteia os NOMES FEMININOS
    function sortearNomeFeminino($sorteioNomeFeminino){
        $indice = array_rand($sorteioNomeFeminino);
        return $sorteioNomeFeminino[$indice];
    }

    // funcao sorteia os SEGUNDOS NOMES FEMININOS
    function sortearSegundoNomeFeminino($sorteioSegundoNomeFeminino){
        $indice = array_rand($sorteioSegundoNomeFeminino);
        return $sorteioSegundoNomeFeminino[$indice];
    }

    // funcao sorteia os SOBRENOMES FEMININOS
    function sortearSobrenomeFeminino($sorteioSobrenomeFeminino){
        $indice = array_rand($sorteioSobrenomeFeminino);
        return $sorteioSobrenomeFeminino[$indice];
    }

    // funcao sorteia o SEXO
    function sortearSexo($sorteioSexo){
        $indice = array_rand($sorteioSexo);
        return $sorteioSexo[$indice];
    }

    // gera CPFs VALIDOS 
    function sortearCpfs($sorteado){
        $num = array();
        $num[9]=$num[10]=$num[11]=0;

            for ($w=0; $w > -2; $w--){
                for($i=$w; $i < 9; $i++){
                    $x=($i-10)*-1;
                    $w==0?$num[$i]=rand(0,9):'';
                    ($w==-1 && $i==$w && $num[11]==0)?
                    $num[11]+=$num[10]*2 :
                    $num[10-$w]+=$num[$i-$w]*$x;
                }
                $num[10-$w]=(($num[10-$w]%11)<2?0:(11-($num[10-$w]%11)));
            };
        $cpfGerado = $num[0].$num[1].$num[2].$num[3].$num[4].$num[5].$num[6].$num[7].$num[8].$num[10].$num[11];
        return $cpfGerado;
    };

    // inicia a VALIDACAO DO CADASTRO PARA GERAR NO BANCO
    if($total){
        $tm_inicio = time(true);
        for ($contaCad = 0; $contaCad < $total; $contaCad++){

                // sorteia o SEXO do cadastro
                $sexo = sortearSexo($sorteioSexo);
            
                // de acordo com o sexo sorteado, sorteia agora o NOME, SEGUNDO NOME, e SOBRENOME do cadastro
                if($sexo == "M"){
                    $nome = sortearNome($sorteioNome).sortearSegundoNome($sorteioSegundoNome).sortearSobrenome($sorteioSobrenome);
                }else{
                    $nome = sortearNomeFeminino($sorteioNomeFeminino).sortearSegundoNomeFeminino($sorteioSegundoNomeFeminino).sortearSobrenomeFeminino($sorteioSobrenomeFeminino);
                };

                // caso o cpf gerado ja esteja cadastrado, volta, e reinicia daqui e gera outro cpf
                inicia_validacao_cpf:

                // chama a funcao, e GERA UM NOVO CPF
                $cpf = (sortearCpfs($sorteado));

                // essa rotina VERIFICA SE O CPF GERADO JA FOI CADASTRADO NO BANCO
                $validaCpf = "SELECT cpf FROM cadastrar WHERE cpf = '$cpf'";
                $query_consulta = mysqli_query($conn, $validaCpf);
                $query_resutado = mysqli_num_rows($query_consulta);
                
                // se o cpf for encontrado no banco...
                if($query_resutado >0){

                    // conta MAIS UM CPF GERADO DUPLICADO
                    $totRepetido++;
                    
                    // retorna e GERA UM NOVO CPF
                    goto inicia_validacao_cpf;
                }else{
                     $totRepetido == 0;
                };
                
                $estadoCpf2 = "";

                // pega o VALOR DO NONO DIGITO do cpf
                $estadoCpf = substr($cpf, 8, 1);

                 // com esse VALOR, ATRIBUI A UMA LINHA DE ESTADO NO ARRAY
                if($estadoCpf==0){$estadoCpf2 = array("RS"=>"a");};
                if($estadoCpf==1){$estadoCpf2 = array("DF"=>"a","GO"=>"b","MS"=>"c","MT"=>"d","TO"=>"e");};
                if($estadoCpf==2){$estadoCpf2 = array("PA"=>"a","AM"=>"b","AC"=>"c","AP"=>"d","RO"=>"e","RR"=>"f");};
                if($estadoCpf==3){$estadoCpf2 = array("CE"=>"a","MA"=>"b","PI"=>"c");};
                if($estadoCpf==4){$estadoCpf2 = array("PE"=>"a","RN"=>"b","PB"=>"c","AL"=>"d");};
                if($estadoCpf==5){$estadoCpf2 = array("BA"=>"a","SE"=>"b");};
                if($estadoCpf==6){$estadoCpf2 = array("MG"=>"a");};
                if($estadoCpf==7){$estadoCpf2 = array("RJ"=>"a","ES"=>"b");};
                if($estadoCpf==8){$estadoCpf2 = array("SP"=>"a");};
                if($estadoCpf==9){$estadoCpf2 = array("PR"=>"a","SC"=>"b");};

                // sorteia UM ESTADO NA LINHA SE ESTADO(OS) SELECIONADA
                $estado = array_rand($estadoCpf2);

                // com o ESTADO SORTEADO, ATRIBUI A UMA LINHA DE DDDs DE ESTADOS
                if($estado == "RS"){$ddd = array("(51)"=>"a","(53)"=>"b","(54)"=>"c","(55)"=>"d");};
                if($estado == "DF"){$ddd = array("(61)"=>"a");};
                if($estado == "GO"){$ddd = array("(61)"=>"a");};
                if($estado == "MS"){$ddd = array("(67)"=>"a");};
                if($estado == "MT"){$ddd = array("(65)"=>"a","(66)"=>"b");};
                if($estado == "TO"){$ddd = array("(63)"=>"a");};
                if($estado == "PA"){$ddd = array("(91)"=>"a","(93)"=>"b");};
                if($estado == "AM"){$ddd = array("(92)"=>"a","(97)"=>"b");};
                if($estado == "AC"){$ddd = array("(68)"=>"a");};
                if($estado == "AP"){$ddd = array("(96)"=>"a");};
                if($estado == "RO"){$ddd = array("(69)"=>"a");};
                if($estado == "RR"){$ddd = array("(95)"=>"a");};
                if($estado == "CE"){$ddd = array("(85)"=>"a");};
                if($estado == "MA"){$ddd = array("(98)"=>"a");};
                if($estado == "PI"){$ddd = array("(86)"=>"a","(89)"=>"b");};
                if($estado == "PE"){$ddd = array("(81)"=>"a","(87)"=>"b");};
                if($estado == "RN"){$ddd = array("(84)"=>"a");};
                if($estado == "AL"){$ddd = array("(82)"=>"a");};
                if($estado == "BA"){$ddd = array("(71)"=>"a");};
                if($estado == "PB"){$ddd = array("(83)"=>"a","(73)"=>"b","(74)"=>"c","(75)"=>"d","(77)"=>"e");};
                if($estado == "SE"){$ddd = array("(79)"=>"a");};
                if($estado == "MG"){$ddd = array("(31)"=>"a","(32)"=>"b","(33)"=>"c","(34)"=>"d","(35)"=>"e","(37)"=>"f");};
                if($estado == "RJ"){$ddd = array("(21)"=>"a","(22)"=>"b","(24)"=>"c");};
                if($estado == "ES"){$ddd = array("(27)"=>"a","(28)"=>"b");};
                if($estado == "SP"){$ddd = array("(11)"=>"a","(12)"=>"b","(13)"=>"c","(14)"=>"d","(15)"=>"e","(16)"=>"f","(17)"=>"g","(19)"=>"h");};
                if($estado == "PR"){$ddd = array("(41)"=>"a","(42)"=>"b","(43)"=>"c","(44)"=>"d","(45)"=>"e","(46)"=>"f");};
                if($estado == "SC"){$ddd = array("(47)"=>"a","(48)"=>"b","(49)"=>"c");};

                // atribui 10 CIDADES AO ESTADO SORTEADO NUMA ARRAY DE CIDADES
                if($estado == "RS"){$cidade2 = array("Alecrim"=>"a","Alvorada"=>"b","Bento Gonçalves"=>"c",
                    "Camargo"=>"e","Gramado"=>"f","Lajeado"=>"g","Montenegro"=>"h","Novo Amburgo"=>"i","Porto Alegre"=>"j");};
                if($estado == "DF"){$cidade2 = array("Samambaia"=>"a","Ceilândia"=>"b","Taguatinga"=>"c","Plano Piloto"=>"d",
                    "Planatina"=>"e","Brasília"=>"f","Gama"=>"g","Paranoá"=>"h","Brazlândia"=>"i","Sobradinho"=>"j");};
                if($estado == "GO"){$cidade2 = array("Goiânia"=>"a","Rio Quente"=>"b","Caldas Novas"=>"c","Anápolis"=>"d",
                    "Aparecida de Goiânia"=>"e","Rio Verde"=>"f","Ouro"=>"g","Vale do Araguaia"=>"h","Águas Quentes"=>"i","Terra Ronca"=>"j");};
                if($estado == "MS"){$cidade2 = array("Campo Grande"=>"a","Dourados"=>"b","Três Lagoas"=>"c","Corumbá"=>"d",
                    "Ponta Porã"=>"e","Bodoquena"=>"f","Costa Rica"=>"g","Miranda"=>"h","Bandeirantes"=>"i","Rio Brilhante"=>"j");};
                if($estado == "MT"){$cidade2 = array("Várzea Grande"=>"a","Cuiabá"=>"b","Rondonópolis"=>"c","Sinop"=>"d","Sorriso"=>"e",
                    "Tangará da Serra"=>"f","Barra do Garça"=>"g","Alta Floresta"=>"h","Paranatinga"=>"i","Juruena"=>"j");};
                if($estado == "TO"){$cidade2 = array("Araguaiana"=>"a","Gurupi"=>"b","Jalapão"=>"c","Palmas"=>"d","Dianópolis"=>"e",
                    "Nova Olinda"=>"f","Ananás"=>"g","Almas"=>"h","Goianorte"=>"i","Nazaré"=>"j");};
                if($estado == "PA"){$cidade2 = array("Belém"=>"a","Ananindeua"=>"b","Santarém"=>"c","Salinópolis"=>"d","Bragança"=>"e",
                    "Ilha do Marajó"=>"f","Serra das Andorinhas"=>"g","Bagre"=>"h","Juruti"=>"i","Terra Santa"=>"j");};
                if($estado == "AM"){$cidade2 = array("Paratins"=>"a","Manaus"=>"b","Itacoatiara"=>"c","Coari"=>"d","Fonte Boa"=>"e",
                    "Canutama"=>"f","Urucará"=>"g","Cadajás"=>"h","Barreirinha"=>"i","Maqnicoré"=>"j");};
                if($estado == "AC"){$cidade2 = array("Cruzeiro do Sul"=>"a","Tarauacá"=>"b","Sena Madureira"=>"c","Feijó"=>"d",
                    "Rio Branco"=>"e","Assis Brasil"=>"f","Plácido de Castro"=>"g","Rodrigues Alves"=>"h","Xapuri"=>"i","Brasiléia"=>"j");};
                if($estado == "AP"){$cidade2 = array("Santana"=>"a","Laranjal do Jari"=>"b","Mazagão"=>"c","Porto Grande"=>"d",
                    "Ferreira Gomes"=>"e","Pracuúba"=>"f","Itaubal"=>"g","Serra do Navio"=>"h","Cutias"=>"i","Macapá"=>"j");};
                if($estado == "RO"){$cidade2 = array("Porto Velho"=>"a","Cacoal"=>"b","Vilhena"=>"c","Jaru"=>"d","Rolim de Moura"=>"e",
                    "Pimenta Bueno"=>"f","Buritis"=>"g","Alto Paraíso"=>"h","Rio Crespo"=>"i","Castanheiras"=>"j");};
                if($estado == "RR"){$cidade2 = array("Boa Vista"=>"a","Rorainópolis"=>"b","Alto Alegre"=>"c","Caracaraí"=>"d",
                    "Pacaraima"=>"e","Normandia"=>"f","Uiramutã"=>"g","Caroebe"=>"h","Mucajaí"=>"i","Cantá"=>"j");};
                if($estado == "CE"){$cidade2 = array("Caucaia"=>"a","Juazeiro do Norte"=>"b","Maracanaú"=>"c","Altaneira"=>"d",
                    "Bela Cruz"=>"e","Jaguaribe"=>"f","Pacoti"=>"g","Fortaleza"=>"h","Russas"=>"i","Sobral"=>"j");};
                if($estado == "MA"){$cidade2 = array("São Luís"=>"a","Timon"=>"b","Balsas"=>"c","Grajaú"=>"d","Colinas"=>"e",
                    "Santa Rita"=>"f","Buriti"=>"g","Mirador"=>"h","MataRoma"=>"i","Lago Verde"=>"j");};
                if($estado == "PI"){$cidade2 = array("Teresina"=>"a","Parnaíba"=>"b","Picos"=>"c","Florianio"=>"d","Bom Jesus"=>"e",
                    "Piripiri"=>"f","Guadalupe"=>"g","Amarante"=>"h","Arraial"=>"i","Batalha"=>"j");};
                if($estado == "PE"){$cidade2 = array("Caruaru"=>"a","Vitória de Sto Antão"=>"b",""=>"c","Recife"=>"d","Olinda"=>"e",
                    "Pombos"=>"f","Ribeirão"=>"g","Surubim"=>"h","Jaboatão dos Guararapes"=>"i","Toritama"=>"j");};
                if($estado == "RN"){$cidade2 = array("Natal"=>"a","Mossoró"=>"b","Parnamirim"=>"c","Extremoz"=>"d","Barra do Cunhaú"=>"e",
                    "Galinhos"=>"f","Touros"=>"g","Nísia Floresta"=>"h","Portalegre"=>"i","Sitio Novo"=>"j");};
                if($estado == "AL"){$cidade2 = array("Maceió"=>"a","Arapiraca"=>"b","Maragogi"=>"c","Delmiro Golveia"=>"d",
                    "Marechal Deodoro"=>"e","Penedo"=>"f","Piranhas"=>"g","União dos Palmares"=>"h","Campestre"=>"i","Taquarana"=>"j");};
                if($estado == "BA"){$cidade2 = array("Porto Seguro"=>"a","Itacaré"=>"b","Canudos"=>"c","Teodoro Sampaio"=>"d",
                    "Salvador"=>"e","Imbassaí"=>"f","Trancoso"=>"g","Prado"=>"h","Caraiva"=>"i","Chapada Diamantina"=>"j");};
                if($estado == "PB"){$cidade2 = array("Campina Grande"=>"a","Santa Rita"=>"b","Patos"=>"c","Conde"=>"d",
                    "João Pessoa"=>"f","Araruna"=>"g","Alagoinha"=>"h","Borborema"=>"i","Queimadas"=>"j");};
                if($estado == "SE"){$cidade2 = array("Aracaju"=>"a","São Cristovão"=>"b","Itabaiana"=>"c","Lagartos"=>"d",
                    "Laranjeiras"=>"e","Estância"=>"f","Aracaju"=>"g","Malhador"=>"h","Poço Verde"=>"i","São Domingos"=>"j");};
                if($estado == "MG"){$cidade2 = array("Uberlândia"=>"a","Contagem"=>"b","Juiz de Fora"=>"c","Belo Horizonte"=>"d",
                    "Betim"=>"e","Barbacena"=>"f","Dom Silvério"=>"g","Jaguaraçu"=>"h","Mesquita"=>"i","Nova Era"=>"j");};
                if($estado == "RJ"){$cidade2 = array("Rio de Janeiro"=>"a","Campos do Goytacazes"=>"b","São Gonçalo"=>"c",
                    "Duque de Caxias"=>"d","Nova Iguaçu"=>"e","Niterói"=>"f","Petrópolis"=>"g","Volta Redonda"=>"h","Macaé"=>"i","Magé"=>"j");};
                if($estado == "ES"){$cidade2 = array("Cariacica"=>"a","Vitória"=>"b","Vila Velha"=>"c","Serra"=>"d",
                    "Cachoeiro do Itapemirim"=>"e","Linhares"=>"f","Guarapari"=>"g","São Mateus"=>"h","Colatina"=>"i","Afonso Cláudio"=>"j");};
                if($estado == "SP"){$cidade2 = array("Limeira"=>"a","Guarulhos"=>"b","Campinas"=>"c","Santo André"=>"d","Santos"=>"e",
                    "Ribeirão Preto"=>"f","São Paulo"=>"g","Carapicuiba"=>"h","Taubaté"=>"i","Presidente Prudente"=>"j");};
                if($estado == "PR"){$cidade2 = array("Curitiba"=>"a","Londrina"=>"b","Maringá"=>"c","Ponta Grossa"=>"d",
                    "Araucária"=>"e","Toledo"=>"f","Campo Largo"=>"g","Apucarana"=>"h","Pinhais"=>"i","Arapongas"=>"j");};
                if($estado == "SC"){$cidade2 = array("Joinville"=>"a","Florianópolis"=>"b","Blumenau"=>"c","Itajaí"=>"d",
                    "Chapecó"=>"e","Criciúma"=>"f","Lages"=>"g","Anchieta"=>"h","Petrolândia"=>"i","Serra Alta"=>"j");};
                
                // sorteia UMA CIDADE NA LINHA DO ESTADO SORTEADO
                $cidade = array_rand($cidade2);

               
                inicia_validacao_tel:
                 // monta o TELEFONE COMPLETO
                $telefone = array_rand($ddd)."9".rand(5111,9999)."-".rand(1000,9999);

                // essa rotina VERIFICA SE O TELEFONE GERADO JA FOI CADASTRADO NO BANCO
                $validaTelefone = "SELECT telefone FROM cadastrar WHERE telefone = '$telefone'";
                $query_telefone = mysqli_query($conn, $validaTelefone);
                $query_resutado_tel = mysqli_num_rows($query_telefone);
                
                // se o telefone for encontrado no banco...
                if($query_resutado_tel >0){
                    $contaTelefone++;
  
                    // retorna e GERA UM NOVO TELEFONE
                    goto inicia_validacao_tel;
                };
                
                //  inicia_validacao_data_nascimento:
                inicia_validacao_data_nascimento:

                // sorteia o ANO DO NASCIMENTO
                $sorteiadataNascimento = rand(1950,date('Y')); 

                // verifica SE E O ANO SORTEADO E BISSEXTO
                $bi = date('L', mktime(0, 0, 0, 1, 1, $sorteiadataNascimento));
                $bissexto = ($bi? 'Sim' : "Não");

                // sorteia o MES DE NASCIMENTO
                $soteioMes = sortearMes($sorteioMes);

                // atribui os dias DE ACORDO COM O MES SORTEADO

                // se o mes for FEVEREIRO E O ANO NÃO FOR BISSEXTO
                if(($soteioMes == "02") AND ($bi == 0)){
                    $dias = rand(01,28);

                // se o mes for FEVEREIRO E O ANO FOR BISSEXTO    
                }elseif(($soteioMes == "02") AND ($bi == 1)){
                    $dias = rand(01,29);

                // se NAO FOR FEVEREIRO, E FOR UM MES DE 31 DIAS    
                }elseif(($soteioMes == "01") OR ($soteioMes == "03") OR ($soteioMes == "05")OR($soteioMes == "07")
                    OR($soteioMes == "08")OR($soteioMes == "10")OR($soteioMes == "12")){
                    $dias = rand(01,31);
                }else{
                    // se NAO FOR FEVEREIRO, E FOR UM MES DE 30 DIAS
                    $dias = rand(01,30);
                };

                // monta a DATA DE NASCIMENTO
                $dataNascimento = $sorteiadataNascimento.'-'.$soteioMes.'-'.$dias;

                // se a data de nascimento FOR MAIOR QUE A DATA ATUAL...
                if($dataNascimento > date('Y-m-a')){

                     // conta uma repetida
                     $contaDataMaior++;

                     // ...retorna e GERA UMA NOVA DATA DE NASCIMENTO
                     goto inicia_validacao_data_nascimento;    

                };

                // verifica A IDADE PELA DATA DE NASCIMENTO GERADA
                $date = new DateTime($dataNascimento); 
                $idade2 = $date->diff(new DateTime( date('Y-m-d')));
                $idade2 = $idade2->format('%Y');

                // atribui varios estados civis, a um array para ser sorteado
                $estadoCivil = array("Solteiro"=>"a","Casado"=>"b","Viuvo"=>"c","Divorciado"=>"d","Amigado"=>"e");
                $estadoCivilJovem = array("Solteiro"=>"a","Casado"=>"b","Amigado"=>"e");

                // se a idade verificada FOR MENOR QUE 18...
                if($idade2 <18){

                    // atribui o valor SOLTEIRO
                    $civil = "Solteiro";
                }elseif(($idade2 >= 18)AND($idade2 <=30)){

                    // estado civil até 30 anos
                    $civil = array_rand($estadoCivilJovem);
                }else{
                    
                    // caso contrario SORTEIA UM ESTADO CIVIL NO ARRAY
                    $civil = array_rand($estadoCivil);
                };

                
                mysqli_query($conn, "SET NAMES 'utf8';");
                $insere = "INSERT INTO cadastrar VALUES ('','$nome','$sexo','$cpf','$cidade','$estado','$telefone','$dataNascimento','$bissexto','$civil')";
                $query_cadastro = mysqli_query($conn, $insere);
            
        };
        
    };
    $tm_fim = time(true);
};

// consulta para CONTAR O TOTAL DE CADASTROS GERADOS NO BANCO
$conta_cadastro = "SELECT id FROM cadastrar";
$executa_contagem = mysqli_query($conn, $conta_cadastro);
$total_cadastro = mysqli_num_rows($executa_contagem);

// consulta para MOSTRAR OS ULTIMOS CADASTROS GERADOS NA TABELA
 mysqli_query($conn, "SET NAMES 'utf8';");
$lista_tabela = mysqli_query($conn,"SELECT * FROM cadastrar ORDER BY id desc LIMIT 15");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        *{
            font-family:sans-serif;
        }
        h1{
                    text-align:center;
                    background :#37a ;
                    padding-top:5px;
                    padding-bottom:5px;
                    color:#fff;
                    border-radius:3px;
                }
                
                button{
                    margin-left:5px;
                    padding:5px;
                }
                input[type=text]{
                    padding:5px;
                    margin-bottom:5px;
                    text-transform:uppercase;
                }
                table{
                    border-collapse: collapse;
                    width: 100%;
                }
                tr:nth-child(odd){
                    background-color:#ccc;
                    border-color:#bbb;
                }
                tr:nth-child(even){
                    background-color:#eee;
                    border-color:#bbb;
                }
             
    </style>
          
</head>
<body>
<h1>Cria 
    cadastro PHP
</h1>
        <div>
            <h2><b><center><?php echo" Total de cadastro ".$total_cadastro?></center></b></h2>
        </div>
        <form class="form" name="formGerarCadastro" action="index.php" method="post">                 
            <div>
                <spam>Total de cadastros a serem criados</spam>
                <input type="text" min="1" maxlength="4"  name="totalCriarCadastro" style="width:5%" id="totalPesquisado" value="" onkeypress='return SomenteNumero(event)'/>
                <button type="submit">Criar</button>
                <button name="excluir" type="submit" onclick="return confirm('Deseja excluir tudo?')" >Excluir tudo</button>
            </div>
        </form>
        <table border="1"  cellpadding="2" cellspacing="0">
                <thead>
                    <tr style="background:#abc">
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Sexo</th>
                        <th>Cpf</th>
                        <th>Local de Nascimento</th>
                        <th>Estado</th>
                        <th>Telefone</th>
                        <th>Dt Nascimento</th>
                        <th>Ano Bissexto</th>
                        <th>Idade</th>
                        <th>Estado Civil</th>
                    </tr>
                </thead>

                <tbody>
                <?php 
                while($linha = mysqli_fetch_array($lista_tabela)) { ?>	
					<tr class="linha_home" >		
						<td style="padding-left:10px;">
                        <?php echo $linha['id']; ?>
		    			</td>
                        <td style="padding-left:10px;">
                            <?php echo $linha['nome']; ?>
                        </td>
                        <td>
                        <center><?php echo $linha['sexo']; ?></center>
                        </td>
                        <td>
                        <center><?php echo $linha['cpf']; ?></center>
                        </td>
                        <td style="padding-left:10px;">
                            <?php echo $linha['cidade']; ?>
                        </td>
                        <td>
                        <center><?php echo $linha['estado']; ?></center>
                        </td>
                        <td>
                        <center><?php echo $linha['telefone']; ?><center>
                        </td>
                        <td>
                        <center><?php 

                            // converte o formato de data americano para o brasileiro
                            echo date('d/m/Y', strtotime($linha['dataNascimento'])) ;
                             ?></center>
                        </td>
                        <td>
                        <center><?php echo $linha['bissexto']; ?></center>
                        </td>
                        <td>
                            <center><?php 
                                    // verifica a idade pela data de nascimento cadastrada
                                    $nascimento = $linha['dataNascimento'];
                                    $date = new DateTime($nascimento); 
                                    $idade = $date->diff(new DateTime( date('Y-m-d')));
                                    echo $idade->format('%Y'); 
                            ?></center>
                        </td>
                        <td style="padding-left:10px;">
                        <?php echo $linha['estadoCivil']; ?>
                        </td>
                    </tr>
                <?php };?>
                </tbody>
        </table>
        <br>
        <?php 
        if(isset($tm_inicio)){ 
            echo "Tempo decorrido - "; 

            // contagem do tempo de execussao
            $tempoDecorrido = $tm_fim - $tm_inicio; 
            $horas = floor($tempoDecorrido/3600);
            $minutos = floor(($tempoDecorrido - ($horas * 3600))/60);
            $segundos = floor($tempoDecorrido % 60);
            
            // mostra o resultado de hora, minuto e segundo, no formato 00:00:00
        echo str_pad($horas,2,0, STR_PAD_LEFT).":".str_pad($minutos,2,0, STR_PAD_LEFT).":".str_pad($segundos,2,0,STR_PAD_LEFT);};
        
        // mostra a quantidade de data inferior a data atual, que gerou 
        if((isset($contaDataMaior) AND ( $contaDataMaior<> 0))){echo "<br>Gerou uma nova data ".$contaDataMaior;};

        // mostra a quantidade de cpf que foram gerados e que ja haviam sido cadastrados
        if((isset($totRepetido) AND ( $totRepetido <> 0))){echo "<br>Gerou um novo cpf ".$totRepetido;};

        // mostra a quantidade de telefone que foram gerados e que ja haviam sido cadastrados
        if((isset($contaTelefone) AND ( $contaTelefone <> 0))){echo "<br>Gerou um novo telefone ".$contaTelefone;};
        echo '<br>';

        // verifica se algum cpf foi duplicado, e agrupa numa lista
        $sql = "SELECT COUNT(cpf)AS contador, cpf FROM cadastrar GROUP BY cpf HAVING COUNT(cpf) >1 ORDER BY count(cpf) DESC";
        $result = $conn->query($sql);
        
        // se houver algum cpf duplicado, gera uma lista
        if($result->num_rows > 0){
            $soma = 0;
            while($row = $result->fetch_assoc()){
               $cpf = $row["cpf"];
               $contador = $row["contador"];
               $soma = $soma + 1;
               echo "<font color=red>Encontrado $soma CPF : $cpf, duplicado $contador vezes <br>";  
               
            };   
        }else{
            echo"Nenhum CPF duplicado foi encontrado<br>";
        };

        // verifica se algum telefone foi duplicado, e agrupa numa lista
        $sql = "SELECT COUNT(telefone)AS contador, telefone FROM cadastrar GROUP BY telefone HAVING COUNT(telefone) >1 ORDER BY count(telefone) DESC";
        $result = $conn->query($sql);
        
       // se houver algum telefone duplicado, gera uma lista
        if($result->num_rows > 0){
            $soma = 0;
            while($row = $result->fetch_assoc()){
               $telefone = $row["telefone"];
               $contador = $row["contador"];
               $soma = $soma + 1;
               echo "<font color=red>Encontrado $soma telefone : $telefone, duplicado: $contador vezes <br>";  
               
            };   
        }else{
            echo"Nenhum Telefone duplicado foi encontrado<br>";
        };

        ?>
           <script src="custom.js"></script>
           <script>

            // permite apenas numeors para escolher um valor a ser criado
            function SomenteNumero(e) {
                var tecla = (window.event) ? event.keyCode : e.which;
                if ((tecla > 47 && tecla < 58))
                    return true;
                else {
                    if (tecla == 8 || tecla == 0 || tecla == 46 || tecla == 44)
                        return true;
                    else
                        return false;
                }
            }
           </script>
</body>
</html>
