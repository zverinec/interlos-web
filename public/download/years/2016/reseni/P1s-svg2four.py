import numpy as np
from math import cos, sin, pi, sqrt, atan2
from svg.path import parse_path
from docopt import docopt
from operator import itemgetter

usage ="""SVG2FOUR

Usage:
  svg2four.py [options] <svg_path>

  Takes SVG path (a string) on input and prints it on stdout with a given
  precision converted to Fouries series.

Options:
  -h --help     Show this screen.
  <svg_path>    String with a single SVG path.
  --vflip       Flip along vertical axis.
  --hflip       Flip along horizontal axis.
  --scale=<sc>  Scale result [default: 1].
  --prec=<p>    Precision [default: 1000].
  --plot        Plot result.
  --filter=<a>  Filter out circlers smaller than 1/a-th of the largest one.
"""

if __name__ == '__main__':
    arguments = docopt(usage)

    path = parse_path(arguments["<svg_path>"])
    res = int(arguments["--prec"])
    scale = float(arguments["--scale"])
    data = np.array([path.point(i / float(res)) * scale for i in range(res)])

    if arguments["--vflip"]:
        data = np.vectorize(lambda x: -x.real + 1j * x.imag)(data)

    if arguments["--hflip"]:
        data = np.vectorize(lambda x: np.conj(x))(data)

    result = np.fft.fft(data)
    # speed, radius, angle
    circles = [(2*pi*i, np.abs(x), atan2(x.imag, x.real)) for i, x in enumerate(result)]
    filt = arguments["--filter"]
    if filt:
        filt = float(filt)
        m = max(circles, key=itemgetter(1))[1]
        circles = filter(lambda x: x[1] > m / filt, circles)
    for s, r, a in circles:
        print("{},{},{}".format(s, r, a))

    if arguments["--plot"]:
        import matplotlib.pyplot as plt
        plt.figure(1)
        plt.subplot(311)
        plt.scatter(data.real, data.imag)
        plt.gca().set_aspect('equal', adjustable='box')

        plt.subplot(312)
        plt.plot(np.abs(result))

        points = []
        res2 = res / 5
        for i in range(res2):
            e = sum([ r * (cos(s*i/res2 + a) + 1j * sin(s*i/res2 + a)) for s, r, a in circles])
            points.append(e)
        points = np.array(points)

        plt.subplot(311)
        plt.scatter(points.real, points.imag)
        plt.gca().set_aspect('equal', adjustable='box')
        plt.show()
