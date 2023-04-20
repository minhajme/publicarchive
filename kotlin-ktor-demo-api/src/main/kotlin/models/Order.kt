package models

import kotlinx.serialization.Serializable

val orderStorage = listOf<Order>(
    Order(
        "2021-08-03-01", listOf(
            OrderItem("Beef Tehari", 5, 3.0),
            OrderItem("Beef Kacci", 6, 3.0),
            OrderItem("Matha(tokdoi, medium)", 11, 1.5)
        )
    ),
    Order(
        "2021-08-03-02", listOf(
            OrderItem("SUV 10 Seater DHK-CTG, Ferry From And To Final Point", 1, 295.5 + 50.5)
        )
    ),
    Order(
        "2021-08-03-03", listOf(
            OrderItem("Air TKT Biman BD DHK-CTG", 1, 67.5),
            OrderItem("Taxi Dhaka Lalbagh to DHK Airport", 1, 22.5),
            OrderItem("Taxi CTG Airport to Wasa Moor", 1, 25.5)
        )
    )
)

@Serializable
data class Order(val number: String, val contents: List<OrderItem>)

@Serializable
data class OrderItem(val item: String, val amount: Int, val price: Double)