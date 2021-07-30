let map = new Map<string, Set<string>>();
map.set("hello", new Set<string>());

console.log(map.get("hello"))

map.get("hello")!.add("asd")

console.log(map.get("hello"));

let set = map.get("hello");

for (let str of set!) {
    console.log(str);
}