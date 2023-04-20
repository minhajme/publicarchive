package routes

import io.ktor.routing.*
import io.ktor.application.*
import io.ktor.http.*
import io.ktor.request.*
import io.ktor.response.*
import models.Customer

import models.customerStorage

fun Route.customerRouting() {
    route("/customer") {
        get {
            if (customerStorage.isNotEmpty()) {
                call.respond(customerStorage)
            } else {
                call.respondText("NotFound: CustomerList: No Customers Found", status = HttpStatusCode.NotFound)
            }
        }
        get("{id}") {
            val id = call.parameters["id"] ?: return@get call.respondText(
                "BadRequest: NotFound: Missing or malformed ID",
                status = HttpStatusCode.BadRequest
            )
            val customer = customerStorage.find { it.id == id.toInt() } ?: return@get call.respondText(
                "NotFound: No customer found with ID#$id",
                status = HttpStatusCode.NotFound
            )
            call.respond(customer)
        }
        post {
            val customer = call.receive<Customer>()
            customerStorage.add(customer)
            call.respondText("SaveSuccess: Customer ${customer.email} save success", status = HttpStatusCode.Created)
        }
        delete("{id}") {
            val id = call.parameters["id"] ?: return@delete call.respond(HttpStatusCode.BadRequest)
            if (customerStorage.removeIf { it.id == id.toInt() }) {
                call.respondText("DeleteSuccess: Customer #{$id} delete success", status = HttpStatusCode.Accepted)
            } else {
                call.respondText("DeleteFailed: Customer#{$id} Not found", status = HttpStatusCode.NotFound)
            }
        }
    }
}

fun Application.registerCustomerRoutes() {
    routing {
        customerRouting()
    }
}