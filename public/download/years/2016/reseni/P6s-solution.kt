#!/usr/bin/env kotlins
/**
    To run this script, install Kotlin Script from here https://github.com/andrewoma/kotlin-script
    The script takes exactly one argument - input file.
*/

import java.io.File
import java.text.Normalizer

fun String.removeDiacritics(): String =
        Normalizer.normalize(this, Normalizer.Form.NFD).replace("\\p{InCombiningDiacriticalMarks}+".toRegex(), "")

/**
 * Used to create a clean file without accents and whitespace. Just for archiving.
 */
@Suppress("unused") fun normalize_file(file: File) {
    file.readLines().forEach {
        val s = it.split(':')
        val name = s[0].trim()
        val stops = s[1].trim().split("->").map { it.trim().toLowerCase().removeDiacritics() }
        println("$name: ${stops.joinToString(separator = " - ")}")
    }
}

fun main(args: Array<String>) {
    val f = File(args[0])
    var originalPath: List<String>? = null
    val edges = f.useLines {
        it.toList().flatMap {
            val s = it.split(':')
            val name = s[0].trim()
            val stops = s[1].split("-").map(String::trim)

            if (name == "1") {  //save path 1 for later
                originalPath = stops
            }

            //create edges
            stops.zip(stops.drop(1))
        }.map {
            //sort pair content to ensure symmetric pairs are removed
            if (it.first < it.second) (it.second to it.first) else it
        }.toSet()
    }

    //compute number of adjacent nodes
    val left= edges.groupBy { it.first }
    val right = edges.groupBy { it.second }
    val counts = left.toMutableMap()
    for ((k, v) in right) {
        if (k in counts) {
            counts[k] = counts[k]!! + v
        } else {
            counts[k] = v
        }
    }
    val components = (edges.groupBy { it.first } + edges.groupBy { it.second })    // (x, {y | (x,y) \in E || (y,x) \in E })
            .map { it.key to it.value.size }                            // count adjacent
            .filter { it.second >= 4 }                                  // filter merge targets
            .map { Component(it.second, it.first, listOf(it.first)) }   // components of centers
            .map { it.extendFromGraph(edges) }                          // components of centers + adjacent nodes
            .fold(listOf<Component>(), { components, component ->
                val canMerge = components.filter { it.shouldMerge(component) }
                val cantMerge = components - canMerge
                val mergedComponent = canMerge.fold(component, Component::plus)

                cantMerge + listOf(mergedComponent)
            })

    //println("${components.size}: $components")

    val newPath = originalPath!!.map { node ->
        components.fold(null as String?, { acc, c ->
            acc ?: if (node in c) c.name else null
        }) ?: node
    }.fold(listOf<String>(), { acc, i ->
        //remove duplicates
        if (acc.lastOrNull() == i) acc else acc + listOf(i)
    })

    println(newPath.joinToString(separator = " - "))
}

data class Component(
        val rank: Int,
        val name: String,
        val nodes: List<String>
) {
    operator fun plus(other: Component): Component
        = if (this.rank < other.rank) {
            other + this
        } else {    //this.rank >= other.rank
            Component(
                rank = this.rank,
                name = if (this.rank != other.rank) this.name else {
                    if (this.name < other.name) this.name else other.name
                },
                nodes = (this.nodes + other.nodes).toSet().toList()
            )
        }

    operator fun contains(node: String): Boolean = node in this.nodes

    fun shouldMerge(other: Component): Boolean = this.nodes.any { it in other.nodes }

    fun extendFromGraph(edges: Iterable<Pair<String, String>>): Component = this.copy(
            nodes = this.nodes + edges.filter {
                    it.first in this.nodes || it.second in this.nodes
                }.map {
                    if (it.first in this.nodes) it.second else it.first
                }
    )

}