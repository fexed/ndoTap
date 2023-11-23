#!/Library/Frameworks/Python.framework/Versions/2.7/bin/python

from PIL import Image, ImageDraw
import sys

img_name = sys.argv[1]
print(img_name + ".png")

im = Image.open(r"../frontend/public/" + img_name + ".png")
w = im.width/100
draw = ImageDraw.Draw(im)
with open(img_name + 'clicked') as coords_file:
    for coords in coords_file:
        coords = coords.strip()
        x = int(coords.split(":")[0].split(".")[0])
        y = int(coords.split(":")[1].split(".")[0])
        #draw.point((int(coords.split(":")[0].split(".")[0]), int(coords.split(":")[1].split(".")[0])), 'red')
        draw.ellipse((x - (w/2), y - (w/2), x + (w/2), y + (w/2)), fill=(255, 0, 0, 255))
im.save("../frontend/public/" + img_name + "_heatmap.png")