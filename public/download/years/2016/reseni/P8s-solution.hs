module Main where

import Control.Monad

zadani :: [Int]
zadani = [1..(2003 * 9697)]


iteration :: [(Int, Int)] -> [(Int, Int)]
iteration xs = filter (\(x, _) -> gcd x l == 1) xs
  where
	l = length xs

main :: IO ()
main = do
	putStrLn $ "it = 0, len = " ++ show (length zadani)
	iter 1 $ map dup zadani
  where
	dup x = (x, x)
	iter i xs = do
		let next = iteration xs
		let nlen = length next
		putStrLn $ "it = " ++ show i ++ ", len = " ++ show nlen
		when (nlen > 1) $ iter (i + 1) (zip [1..nlen] (map snd next))
		when (nlen == 2) $ print (snd (last next))
