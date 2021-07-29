/**
 * https://deno.land/x/async/mod.ts
 * bundled to JS with deno bundle.
 */

function deferred() {
    let methods;
    const promise = new Promise((resolve, reject)=>{
        methods = {
            resolve,
            reject
        };
    });
    return Object.assign(promise, methods);
}
class Lock {
    #waiters;
    constructor(){
        this.#waiters = [];
    }
    async with(callback) {
        await this.acquire();
        try {
            await callback() ?? Promise.resolve();
        } finally{
            this.release();
        }
    }
    async acquire() {
        const waiters = [
            ...this.#waiters
        ];
        this.#waiters.push(deferred());
        if (waiters.length) {
            await Promise.all(waiters);
        }
        return true;
    }
    release() {
        const waiter = this.#waiters.shift();
        if (waiter) {
            waiter.resolve();
        } else {
            throw new Error("The lock is not locked");
        }
    }
    locked() {
        return !!this.#waiters.length;
    }
}
class Event1 {
    #waiter;
    constructor(){
        this.#waiter = deferred();
    }
    async wait() {
        if (this.#waiter) {
            await this.#waiter;
        }
        return true;
    }
    set() {
        if (this.#waiter) {
            this.#waiter.resolve();
            this.#waiter = null;
        }
    }
    clear() {
        if (!this.#waiter) {
            this.#waiter = deferred();
        }
    }
    is_set() {
        return !this.#waiter;
    }
}
class Condition {
    #lock;
    #waiters;
    constructor(lock){
        this.#lock = lock ?? new Lock();
        this.#waiters = [];
    }
    async with(callback) {
        await this.acquire();
        try {
            await callback() ?? Promise.resolve();
        } finally{
            this.release();
        }
    }
    async acquire() {
        await this.#lock.acquire();
        return true;
    }
    release() {
        this.#lock.release();
    }
    locked() {
        return this.#lock.locked();
    }
    notify(n = 1) {
        if (!this.locked()) {
            throw new Error("The lock is not acquired");
        }
        for (const _ of Array(n)){
            const waiter = this.#waiters.shift();
            if (!waiter) {
                break;
            }
            waiter.set();
        }
    }
    notify_all() {
        this.notify(this.#waiters.length);
    }
    async wait() {
        if (!this.locked()) {
            throw new Error("The lock is not acquired");
        }
        const event = new Event1();
        this.#waiters.push(event);
        this.release();
        await event.wait();
        await this.acquire();
        return true;
    }
    async wait_for(predicate) {
        while(!predicate()){
            await this.wait();
        }
    }
}
class QueueEmpty extends Error {
}
class QueueFull extends Error {
}
export class Queue {
    #queue;
    #maxsize;
    #full_notifier;
    #empty_notifier;
    constructor(maxsize = 0){
        this.#queue = [];
        this.#maxsize = maxsize <= 0 ? 0 : maxsize;
        this.#full_notifier = new Condition();
        this.#empty_notifier = new Condition();
    }
    empty() {
        return !this.#queue.length;
    }
    full() {
        return !!this.#maxsize && this.#queue.length === this.#maxsize;
    }
    async get() {
        const value = this.#queue.shift();
        if (!value) {
            return new Promise((resolve)=>{
                this.#empty_notifier.with(async ()=>{
                    await this.#empty_notifier.wait_for(()=>!!this.#queue.length
                    );
                    resolve(await this.get());
                });
            });
        }
        await this.#full_notifier.with(()=>{
            this.#full_notifier.notify();
        });
        return value;
    }
    get_nowait() {
        const value = this.#queue.shift();
        if (!value) {
            throw new QueueEmpty("Queue empty");
        }
        this.#full_notifier.with(()=>{
            this.#full_notifier.notify();
        });
        return value;
    }
    async put(value) {
        if (this.#maxsize && this.#queue.length >= this.#maxsize) {
            await this.#full_notifier.with(async ()=>{
                await this.#full_notifier.wait_for(()=>this.#queue.length < this.#maxsize
                );
                await this.put(value);
            });
            return;
        }
        await this.#empty_notifier.with(()=>{
            this.#empty_notifier.notify();
        });
        this.#queue.push(value);
    }
    put_nowait(value) {
        if (this.#maxsize && this.#queue.length >= this.#maxsize) {
            throw new QueueFull("Queue full");
        }
        this.#empty_notifier.with(()=>{
            this.#empty_notifier.notify();
        });
        this.#queue.push(value);
    }
    qsize() {
        return this.#queue.length;
    }
}
class Semaphore {
    value;
    #lock;
    constructor(value = 1){
        if (value < 0) {
            throw new Error("The value must be greater than 0");
        }
        this.#lock = new Lock();
        this.value = value;
    }
    async with(callback) {
        await this.acquire();
        try {
            await callback() ?? Promise.resolve();
        } finally{
            this.release();
        }
    }
    async acquire() {
        if (this.value > 0) {
            this.value -= 1;
        }
        if (this.value === 0) {
            await this.#lock.acquire();
        }
        return true;
    }
    release() {
        if (this.#lock.locked()) {
            this.#lock.release();
        }
        if (!this.#lock.locked()) {
            this.value += 1;
        }
    }
    locked() {
        return this.value === 0;
    }
}
class BoundedSemaphore extends Semaphore {
    #bound;
    constructor(value1 = 1){
        super(value1);
        this.#bound = value1;
    }
    release() {
        if (this.value === this.#bound) {
            throw new Error("release() cannot be called more than acquire() with BoundedSemaphore");
        }
        super.release();
    }
}
