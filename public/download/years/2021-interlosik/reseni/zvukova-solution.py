import wave
import struct

file = wave.open("nahravka.wav")
samples = []

frame = file.readframes(1)
while frame:
    samples.append(struct.unpack("h", frame)[0]/32767)
    frame = file.readframes(1)

segment = samples[:22050]  # první segment

lengths = set()
counter = 0

for i in range(len(segment)-1):
    # když amplituda přejde z - do +, skončila perioda a zapíše se její délka
    if segment[i] < 0 and segment[i+1] >= 0:
        lengths.add(counter)
        counter = 0
    else:
        counter += 1

print(lengths)  # vypíše všechny různé délky period ve vybraném segmentu
