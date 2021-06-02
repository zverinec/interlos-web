from typing import Dict, Optional, List, Tuple, Set, Iterable, Any
import csv
import sys

money = {"IEUR": 88.0,
         "SD": 34,
         "JR": 71,
         "SL": 66,
         "DF": 53,
         "JJ": 71,
         "VD": 95,
         "SM": 36}

currencies = list(money.keys())


def maybe_float(x: str) -> Optional[float]:
    if x == '':
        return None
    return float(x)


def decode_csv(data: Any) -> Dict[str, Dict[str, float]]:
    # read the header first so we can build dictionary for each currency
    # we skip the first element of the header too ("směnárna")
    head = next(data)[1:]
    out = {}
    # for each of the remaining lines get the name of the exchange and the
    # list of exchange rates
    for exchange, *rates in data:
        out[exchange] = {cur: float(rate)
                         for cur, rate in zip(head, rates)
                         if rate != ''}
    return out


rates = decode_csv(csv.reader(open(sys.argv[1] if len(sys.argv) > 1 else "kurzy.csv")))

exchanges = list(rates.keys())

surcharges = {"Za Jezerem": (2000, 700),
              "Před Smrkem": (1500, 550),
              "Na jedno kopyto": (1000, 470)}

double_bonus = {"U Bažiny": 0.2}
single_bonus = {"Bohaté paroží": 0.1,
                "Stříbrná bobule": 0.1,
                "Za Jezerem": 0.1,
                "Před Smrkem": 0.1}


def gen_mapping(options: List[str], lenght: int) -> List[List[str]]:
    if lenght == 0:
        return [[]]
    shorter = gen_mapping(options, lenght - 1)
    out: List[List[str]] = []
    for opt in options:
        for s in shorter:
            out.append([opt] + s)
    return out


ExchangeMapping = Dict[str, Set[str]]


def make_exchange_list(base_mapping: Iterable[Tuple[str, str]])\
        -> ExchangeMapping:
    out: ExchangeMapping = {}
    for currency, exchange in base_mapping:
        if exchange not in out:
            out[exchange] = {currency}
        else:
            out[exchange].add(currency)
    return out


options = (make_exchange_list(zip(currencies, mapping))
           for mapping in gen_mapping(exchanges, len(currencies)))


def evaluate(mapping: ExchangeMapping, amounts: Dict[str, float]) \
        -> Optional[float]:
    grand_total = 0.0
    for exchange, currencies in mapping.items():
        exchanged = []
        for cur in currencies:
            if cur in amounts and cur not in rates[exchange]:
                # this cannot be done, we are using excnage that cannot
                # exchange some of the currencies
                return None
            exchanged.append(amounts.get(cur, 0) * rates[exchange].get(cur, 0))

        exchanged.sort(reverse=True)
        base_amount = sum(exchanged)
        # print(f"base: {exchange}: {base_amount} ({exchanged})")

        local_total = base_amount \
            + single_bonus.get(exchange, 0) * exchanged[0]
        if exchange in double_bonus and len(exchanged) >= 2:
            local_total += double_bonus[exchange] * sum(exchanged[0:2])
        # print(f"before surcharges: {local_total}")
        thresh, sur = surcharges.get(exchange, (0, 0))
        if base_amount < thresh:
            local_total -= sur
        grand_total += local_total
    return grand_total


def run() -> Tuple[float, str]:
    max_amount = 0.0
    max_solutions: Set[str] = set()
    for opt in options:
        total = evaluate(opt, money)
        if total is None:
            continue
        if total >= max_amount:
            if total > max_amount:
                max_solutions = set()
            max_amount = total
            max_solution = ''
            for c in currencies:
                for e, cs in opt.items():
                    if c in cs:
                        max_solution += e[0]
            max_solutions.add(max_solution)
    assert len(max_solutions) == 1, f"{max_solutions} for {max_amount}"
    return (max_amount, next(iter(max_solutions)))


if __name__ == "__main__":
    print(run())
