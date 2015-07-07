#!/bin/sh
clear

source /usr/bin/shellscripts/common.sh


title 'Compiling Nginx..'


# install mercurial if needed
if [ ! -f /usr/bin/hg ]; then
	yum install -y mercurial
fi
# clone repo
if [ -d /usr/local/src/nginx/ ]; then
	echo -n 'cd> '
	pushd /usr/local/src/nginx/
		hg update --clean \
			|| exit 1
	echo -n 'cd< '
	popd
else
	echo -n 'cd> '
	pushd /usr/local/src/
		hg clone http://hg.nginx.org/nginx/ \
			|| exit 1
	echo -n 'cd< '
	popd
fi


# find nginx version
echo -n 'cd> '
pushd /usr/local/src/nginx/
	RELEASE=`hg tags | grep release-1.8. | head -n1 | awk -F' ' '{print $1}' | awk -F'-' '{print $2}'`
	if [ -z $RELEASE ]; then
		echo 'Failed to find latest version!'
		exit 1
	fi
	title "Nginx Version ${RELEASE}"
	hg update "release-${RELEASE}" \
		|| exit 1
echo -n 'cd< '
popd


### prerequisites


# pcre
if [ ! -f /usr/include/pcre.h ]; then
	yum install -y pcre pcre-devel || exit 1
fi
# zlib
if [ ! -f /usr/include/zlib.h ]; then
	yum install -y zlib zlib-devel || exit 1
fi
if [ ! -f /usr/include/openssl/ssl3.h ]; then
	yum install -y openssl openssl-devel || exit 1
fi
if [ ! -f /usr/bin/gcc ]; then
	yum groupinstall -y 'Development Tools' || exit 1
fi


# compile
echo -n 'cd> '
pushd /usr/local/src/nginx/
	./auto/configure \
		--prefix=/usr/local/nginx-${RELEASE} \
		--conf-path=/etc/nginx/nginx.conf \
		--error-log-path=/var/log/nginx/error.log \
		--pid-path=/run/nginx.pid \
		--lock-path=/var/lock/nginx.lock \
		--http-log-path=/var/log/access.log \
		--without-http_ssi_module \
		--with-http_ssl_module \
		--with-http_stub_status_module \
			|| exit 1
	make         || exit 1
	make install || exit 1
echo -n 'cd< '
popd
ln -sfT "/usr/local/nginx-${RELEASE}/" /usr/local/nginx \
	|| exit 1

title "Nginx ${RELEASE} Successfully Installed!"
