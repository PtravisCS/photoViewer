#!/bin/bash

count=(`find ./ -maxdepth 1 -name "*.jpg"`)

if [ ${#count[@]} -gt 0 ]; then

	cp ./*.jpg ../img/ 
	mogrify -resize 250x250 *.jpg 
	mv ./*.jpg ../thumbs/

fi

count=(`find ./ -maxdepth 1 -name "*.mp4"`)

if [ ${#count[@]} -gt 0 ]; then

  for f in *.mp4; do 
    mv "$f" "$(echo "$f" | sed -E s/VID/IMG/I)" 
  done

  rename 's/VID/IMG/I' *.mp4

	for i in *.mp4; do
	    [ -f "$i" ] || break
	    j=${i%.*}
	    ffmpeg -i $i -ss 00:00:01.000 -vframes 1 "$j.png" > /dev/null 2> /dev/null
	done

	mogrify -resize 250x250 *.png
	mv ./*.png ../thumbs/
	mv ./*.mp4 ../img/

fi
