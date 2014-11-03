COMPOSER := $(shell if [ `which composer` ]; then echo 'composer'; else curl -sS https://getcomposer.org/installer | php > /dev/null 2>&1 ; echo './composer.phar'; fi;)

update:
	git pull -u origin master
	$(COMPOSER) update --no-dev

install:
	$(COMPOSER) install --no-dev

hoa:
	git clone https://github.com/hoaproject/Central data/Central --depth 1