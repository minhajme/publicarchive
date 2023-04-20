package com.jetbrains.handson.httpapi

import io.ktor.application.*
import io.ktor.features.*
import io.ktor.serialization.*
import routes.registerCustomerRoutes
import routes.registerOrderRoutes


fun main(args: Array<String>): Unit = io.ktor.server.netty.EngineMain.main(args)

fun Application.module(testing: Boolean = false) {
    install(ContentNegotiation) {
        json()
    }
    registerCustomerRoutes()
    registerOrderRoutes()
}
