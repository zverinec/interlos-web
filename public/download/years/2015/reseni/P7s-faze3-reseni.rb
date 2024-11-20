require 'rmagick'
include Magick

image = Image.new(2000, 2000)

while line = gets
    coords = line.split(' ').map { |s| s.to_i }
    image.pixel_color(coords[0], coords[1], 'black')
end

image.write('r4.png')
